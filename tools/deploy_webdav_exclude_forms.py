#!/usr/bin/env python3
import argparse
import base64
import datetime
import email.utils
import os
import sys
import urllib.error
import urllib.parse
import urllib.request
import xml.etree.ElementTree as ET


EXCLUDED_DIRS = {
    '.git',
    '.cybercreek',
    'hts-cache',
}
EXCLUDED_FILES = {
    'hts-log.txt',
}
EXCLUDED_ROOT_PREFIXES = (
    'contact-us',
    'appointments',
)


def normalize_webdav_url(raw: str) -> str:
    raw = raw.strip()
    if not raw:
        return raw

    # Handle cPanel/Windows-style WebDAV strings like:
    # example.com@SSL@2078\DavWWWRoot
    for marker in ('@SSL@', '@TLS@'):
        if marker in raw:
            host, rest = raw.split(marker, 1)
            scheme = 'https'
            rest = rest.lstrip('\\/')
            port = ''
            path = ''
            if rest:
                parts = rest.replace('\\', '/').split('/', 1)
                if parts:
                    port = parts[0]
                if len(parts) > 1:
                    path = parts[1]
            host = host.strip()
            port = port.strip()
            path = path.strip()
            base = f'{scheme}://{host}'
            if port.isdigit():
                base += f':{port}'
            if path:
                base += '/' + path.lstrip('/')
            return base.rstrip('/') + '/'

    # Replace backslashes for any other Windows-style paths.
    raw = raw.replace('\\', '/')

    if '://' not in raw:
        raw = 'https://' + raw

    parsed = urllib.parse.urlsplit(raw)
    netloc = parsed.netloc
    if '@' in netloc:
        # Strip userinfo if embedded.
        netloc = netloc.split('@', 1)[1]
    path = parsed.path or '/'
    if not path.endswith('/'):
        path += '/'
    rebuilt = urllib.parse.urlunsplit((parsed.scheme, netloc, path, '', ''))
    return rebuilt


def load_creds(path: str) -> dict:
    if not os.path.isfile(path):
        raise FileNotFoundError(f'Credentials file not found: {path}')
    url = None
    user = None
    password = None
    with open(path, 'r', encoding='utf-8') as handle:
        for raw in handle:
            line = raw.strip()
            if not line:
                continue
            lower = line.lower()
            if lower.startswith(('user:', 'username:', 'login:')):
                user = line.split(':', 1)[1].strip()
                continue
            if lower.startswith(('pass:', 'password:')):
                password = line.split(':', 1)[1].strip()
                continue
            if lower.startswith(('url:', 'host:', 'webdisk:')):
                url = line.split(':', 1)[1].strip()
                continue
            if url is None:
                url = line.strip()

    if not url:
        raise ValueError('Missing WebDAV URL in credentials file.')

    url = normalize_webdav_url(url)

    return {
        'url': url,
        'user': user,
        'password': password,
    }


class WebDAVClient:
    def __init__(self, base_url, user=None, password=None, debug=False):
        self.base_url = base_url.rstrip('/') + '/'
        self.auth_header = None
        self.debug = debug
        if user is not None and password is not None:
            token = f'{user}:{password}'.encode('utf-8')
            self.auth_header = 'Basic ' + base64.b64encode(token).decode('ascii')

    def request(self, method, url, data=None, headers=None):
        headers = headers or {}
        req = urllib.request.Request(url, data=data, method=method)
        if self.auth_header:
            req.add_header('Authorization', self.auth_header)
        if method == 'PROPFIND':
            req.add_header('Depth', headers.get('Depth', '0'))
            headers.pop('Depth', None)
        for key, value in headers.items():
            req.add_header(key, value)
        try:
            with urllib.request.urlopen(req) as response:
                body = response.read()
                if self.debug:
                    print(f'DEBUG {method} {url} -> {response.status}', file=sys.stderr)
                return response.status, body, response.headers
        except urllib.error.HTTPError as err:
            body = err.read()
            if self.debug:
                print(f'DEBUG {method} {url} -> {err.code}', file=sys.stderr)
            return err.code, body, err.headers
        except Exception:
            if self.debug:
                print(f'DEBUG {method} {url} -> error', file=sys.stderr)
            return None, None, None

    def url_for(self, rel_path):
        rel_path = rel_path.lstrip('/')
        if rel_path:
            parts = [urllib.parse.quote(part) for part in rel_path.split('/')]
            rel_path = '/'.join(parts)
        return urllib.parse.urljoin(self.base_url, rel_path)


def parse_propfind(body: bytes):
    try:
        root = ET.fromstring(body)
    except ET.ParseError:
        return None
    ns = {'d': 'DAV:'}
    prop = root.find('.//d:prop', ns)
    if prop is None:
        return None
    size_text = prop.findtext('d:getcontentlength', default='', namespaces=ns).strip()
    mtime_text = prop.findtext('d:getlastmodified', default='', namespaces=ns).strip()
    size = None
    if size_text.isdigit():
        size = int(size_text)
    elif size_text:
        try:
            size = int(float(size_text))
        except ValueError:
            size = None
    mtime = None
    if mtime_text:
        try:
            dt = email.utils.parsedate_to_datetime(mtime_text)
            if dt and dt.tzinfo is None:
                dt = dt.replace(tzinfo=datetime.timezone.utc)
            if dt:
                mtime = dt.timestamp()
        except Exception:
            mtime = None
    return size, mtime


def remote_stat(client: WebDAVClient, rel_path: str):
    url = client.url_for(rel_path)
    propfind_body = b"""<?xml version="1.0" encoding="utf-8"?>
<d:propfind xmlns:d="DAV:">
  <d:prop>
    <d:getcontentlength />
    <d:getlastmodified />
  </d:prop>
</d:propfind>
"""
    status, body, headers = client.request(
        'PROPFIND',
        url,
        data=propfind_body,
        headers={'Depth': '0', 'Content-Type': 'text/xml'},
    )
    if status in (200, 207) and body:
        parsed = parse_propfind(body)
        if parsed and (parsed[0] is not None or parsed[1] is not None):
            return parsed
    # Fallback to HEAD if PROPFIND failed or returned no metadata.
    status, _body, headers = client.request('HEAD', url)
    if status is None or status == 404:
        return None
    if status and status >= 400:
        return None
    size = None
    mtime = None
    if headers:
        length = headers.get('Content-Length')
        modified = headers.get('Last-Modified')
        if length and length.isdigit():
            size = int(length)
        if modified:
            try:
                dt = email.utils.parsedate_to_datetime(modified)
                if dt and dt.tzinfo is None:
                    dt = dt.replace(tzinfo=datetime.timezone.utc)
                if dt:
                    mtime = dt.timestamp()
            except Exception:
                mtime = None
    if size is None and mtime is None:
        return None
    return size, mtime


def probe_auth(client: WebDAVClient):
    propfind_body = b"""<?xml version="1.0" encoding="utf-8"?>
<d:propfind xmlns:d="DAV:">
  <d:prop>
    <d:getcontentlength />
    <d:getlastmodified />
  </d:prop>
</d:propfind>
"""
    status, _body, _headers = client.request(
        'PROPFIND',
        client.base_url,
        data=propfind_body,
        headers={'Depth': '0', 'Content-Type': 'text/xml'},
    )
    if status in (401, 403):
        return False, status
    if status is None:
        return False, None
    if status == 405:
        # Fall back to HEAD for servers that don't allow PROPFIND on the collection.
        status, _body, _headers = client.request('HEAD', client.base_url)
        if status in (401, 403):
            return False, status
        if status is None:
            return False, None
    return True, status


def should_upload(local_path: str, remote_info):
    local_size = os.path.getsize(local_path)
    local_mtime = os.path.getmtime(local_path)
    if remote_info is None:
        return True
    remote_size, remote_mtime = remote_info
    if remote_size is None or remote_size != local_size:
        return True
    if remote_mtime is None:
        return True
    return remote_mtime < local_mtime


def ensure_remote_dirs(client: WebDAVClient, rel_path: str, created: set):
    parts = rel_path.strip('/').split('/')[:-1]
    if not parts:
        return
    path = ''
    for part in parts:
        path = f'{path}{part}/'
        if path in created:
            continue
        url = client.url_for(path)
        status, _body, _headers = client.request('MKCOL', url)
        if status in (201, 200, 204, 405):
            created.add(path)
            continue
        if status == 409:
            created.add(path)
            continue
        created.add(path)


def iter_files(root: str):
    for dirpath, dirnames, filenames in os.walk(root):
        rel_dir = os.path.relpath(dirpath, root)
        if rel_dir == '.':
            rel_dir = ''
        pruned = []
        for dirname in dirnames:
            if dirname in EXCLUDED_DIRS:
                continue
            pruned.append(dirname)
        dirnames[:] = pruned

        for filename in filenames:
            rel_path = os.path.normpath(os.path.join(rel_dir, filename))
            if rel_path in EXCLUDED_FILES:
                continue
            if rel_dir == '' and filename.startswith(EXCLUDED_ROOT_PREFIXES):
                continue
            yield os.path.join(dirpath, filename), rel_path.replace(os.sep, '/')


def upload_file(client: WebDAVClient, rel_path: str, local_path: str):
    with open(local_path, 'rb') as handle:
        data = handle.read()
    url = client.url_for(rel_path)
    status, _body, _headers = client.request('PUT', url, data=data, headers={'Content-Type': 'application/octet-stream'})
    return status in (200, 201, 204)


def main():
    parser = argparse.ArgumentParser(description='WebDAV deploy (safe, no deletes).')
    parser.add_argument('--creds', default='.cybercreek/webdisk.creds', help='Path to WebDAV credentials file.')
    parser.add_argument('--base-url', default=None, help='Override WebDAV base URL.')
    parser.add_argument('--user', default=None, help='Override WebDAV username.')
    parser.add_argument('--password', default=None, help='Override WebDAV password.')
    parser.add_argument('--root', default='.', help='Local root to deploy.')
    parser.add_argument('--debug', action='store_true', help='Print debug info for metadata requests.')
    parser.add_argument('--apply', action='store_true', help='Actually upload files. Default is dry-run.')
    args = parser.parse_args()

    creds = load_creds(args.creds)
    base_url = args.base_url or creds['url']
    user = args.user or creds['user']
    password = args.password or creds['password']

    client = WebDAVClient(base_url, user, password, debug=args.debug)
    created_dirs = set()

    ok, status = probe_auth(client)
    if not ok:
        if status in (401, 403):
            print('Auth failed (HTTP %s). Check WebDAV username/password or base URL.' % status)
        else:
            print('Unable to reach WebDAV endpoint. Check base URL and connectivity.')
        sys.exit(2)

    uploaded = 0
    skipped = 0
    errors = 0
    missing_meta = 0

    for local_path, rel_path in iter_files(args.root):
        remote_info = remote_stat(client, rel_path)
        if remote_info is None:
            missing_meta += 1
        if not should_upload(local_path, remote_info):
            skipped += 1
            continue

        if not args.apply:
            uploaded += 1
            continue

        ensure_remote_dirs(client, rel_path, created_dirs)
        ok = upload_file(client, rel_path, local_path)
        if ok:
            uploaded += 1
        else:
            errors += 1

    mode = 'APPLY' if args.apply else 'DRY-RUN'
    print(f'{mode} summary: {uploaded} to upload, {skipped} skipped, {errors} errors, {missing_meta} missing metadata')
    if errors:
        sys.exit(1)


if __name__ == '__main__':
    main()
