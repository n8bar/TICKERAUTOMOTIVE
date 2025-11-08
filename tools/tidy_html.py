#!/usr/bin/env python3
"""Deterministic HTML tidier for HTTrack mirrors.

Removes HTTrack noise, normalizes DOCTYPE/tag casing, and enforces a simple
indentation/whitespace style so diffs stay readable. Uses only the stdlib so it
can run in constrained environments.
"""

from __future__ import annotations

import argparse
import pathlib
import re
from dataclasses import dataclass
from html.parser import HTMLParser
from typing import Iterable

VOID_TAGS = {
    "area",
    "base",
    "br",
    "col",
    "embed",
    "hr",
    "img",
    "input",
    "link",
    "meta",
    "param",
    "source",
    "track",
    "wbr",
}

RAW_TEXT_TAGS = {"script", "style"}


@dataclass
class Token:
    kind: str
    tag: str | None = None
    attrs: list[tuple[str, str | None]] | None = None
    data: str | None = None


class RecordingParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__(convert_charrefs=False)
        self.tokens: list[Token] = []

    def error(self, message: str) -> None:  # pragma: no cover - HTMLParser hook
        raise RuntimeError(message)

    def handle_decl(self, decl: str) -> None:
        self.tokens.append(Token("decl", data=decl.strip()))

    def handle_starttag(self, tag: str, attrs) -> None:
        self.tokens.append(Token("start", tag=tag, attrs=list(attrs)))

    def handle_endtag(self, tag: str) -> None:
        self.tokens.append(Token("end", tag=tag))

    def handle_startendtag(self, tag: str, attrs) -> None:
        self.tokens.append(Token("startend", tag=tag, attrs=list(attrs)))

    def handle_comment(self, data: str) -> None:
        trimmed = data.strip()
        if "HTTrack" in trimmed:
            return
        self.tokens.append(Token("comment", data=trimmed))

    def handle_data(self, data: str) -> None:
        self._append_data(data)

    def handle_entityref(self, name: str) -> None:
        self._append_data(f"&{name};")

    def handle_charref(self, name: str) -> None:
        self._append_data(f"&#{name};")

    def _append_data(self, chunk: str) -> None:
        if not chunk:
            return
        if self.tokens and self.tokens[-1].kind == "data":
            self.tokens[-1].data += chunk
        else:
            self.tokens.append(Token("data", data=chunk))


WHITESPACE_RE = re.compile(r"\s+")


def tidy_tokens(tokens: Iterable[Token], indent: str = "    ") -> str:
    lines: list[str] = []
    stack: list[str] = []
    current_indent = 0

    def write_line(text: str) -> None:
        if not text:
            return
        lines.append(text.rstrip())

    for token in tokens:
        kind = token.kind
        tag = (token.tag or "").lower()
        if kind == "decl":
            if token.data and token.data.lower().startswith("doctype"):
                write_line("<!DOCTYPE html>")
            else:
                write_line(f"<!{token.data}>")
        elif kind == "comment":
            if token.data:
                write_line(f"{indent * current_indent}<!-- {token.data} -->")
        elif kind in {"start", "startend"}:
            if tag == "meta" and has_content_type_http_equiv(token.attrs or []):
                continue
            attrs = format_attrs(token.attrs or [])
            opening = f"<{tag}{attrs}>"
            write_line(f"{indent * current_indent}{opening}")
            if kind == "startend" or tag in VOID_TAGS:
                continue
            stack.append(tag)
            current_indent += 1
        elif kind == "end":
            if current_indent:
                current_indent -= 1
            write_line(f"{indent * current_indent}</{tag}>")
            if stack and stack[-1] == tag:
                stack.pop()
        elif kind == "data" and token.data:
            if stack and stack[-1] in RAW_TEXT_TAGS:
                raw_block = token.data.rstrip("\n")
                raw_lines = raw_block.splitlines()
                meaningful = [line for line in raw_lines if line.strip()]
                base_indent = min(
                    (len(line) - len(line.lstrip()) for line in meaningful),
                    default=0,
                )
                for raw in raw_lines:
                    trimmed = raw[base_indent:] if base_indent else raw
                    write_line(f"{indent * current_indent}{trimmed.rstrip()}")
            else:
                cleaned = WHITESPACE_RE.sub(" ", token.data)
                cleaned = cleaned.strip()
                if cleaned:
                    write_line(f"{indent * current_indent}{cleaned}")
    # collapse multiple blank lines
    compact: list[str] = []
    previous_blank = False
    for line in lines:
        is_blank = not line.strip()
        if is_blank and previous_blank:
            continue
        compact.append(line)
        previous_blank = is_blank
    compact.append("")  # ensure trailing newline
    return "\n".join(compact)


def has_content_type_http_equiv(attrs: list[tuple[str, str | None]]) -> bool:
    for name, value in attrs:
        if name.lower() == "http-equiv" and (value or "").lower() == "content-type":
            return True
    return False


def format_attrs(attrs: list[tuple[str, str | None]]) -> str:
    if not attrs:
        return ""
    rendered = []
    for name, value in attrs:
        if value is None:
            rendered.append(name)
        else:
            escaped = (
                value.replace("&", "&amp;")
                .replace('"', "&quot;")
                .replace("<", "&lt;")
                .replace(">", "&gt;")
            )
            rendered.append(f'{name}="{escaped}"')
    return " " + " ".join(rendered)


def find_targets(paths: list[str]) -> list[pathlib.Path]:
    html_files: set[pathlib.Path] = set()
    for raw in paths:
        target = pathlib.Path(raw)
        if target.is_dir():
            for file in target.rglob("*.html"):
                html_files.add(file)
        elif target.suffix.lower() in {".html", ".htm"}:
            html_files.add(target)
    return sorted(html_files)


def process_file(path: pathlib.Path, dry_run: bool = False) -> bool:
    original = path.read_text(encoding="utf-8")
    parser = RecordingParser()
    parser.feed(original)
    parser.close()
    formatted = tidy_tokens(parser.tokens)
    if formatted == original:
        return False
    if not dry_run:
        path.write_text(formatted, encoding="utf-8")
    return True


def main() -> None:
    parser = argparse.ArgumentParser(description="Tidy HTTrack HTML output.")
    parser.add_argument(
        "paths",
        nargs="+",
        help="HTML files or directories (recursively processed).",
    )
    parser.add_argument(
        "--check",
        action="store_true",
        help="Only report files that would change.",
    )
    args = parser.parse_args()
    targets = find_targets(args.paths)
    if not targets:
        parser.error("no HTML files found in the provided paths")
    changed = []
    for file in targets:
        if process_file(file, dry_run=args.check):
            changed.append(file)
    if args.check and changed:
        print("Files needing tidy:")
        for file in changed:
            print(f" - {file}")
        raise SystemExit(1)


if __name__ == "__main__":
    main()
