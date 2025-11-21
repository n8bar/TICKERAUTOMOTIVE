# Repository Guidelines

## Project Structure & Module Organization
- Root-level HTML pages (`index.html`, `appointments.html`, promo landing pages) stay deploy-ready for tickerautomotive.com.
- CDN-derived assets live under their source folders (for example `app.multiscreenstore.com/`, `services/`, `vehicles/`); drop new files beside their closest match so relative paths still resolve.
- Shared behaviour sits in `index.js` and the service worker stub `f30f4.txt`; update both whenever you change caching or device logic.
- Keep HTTrack artifacts (`hts-cache/`, `hts-log.txt`) untouched unless you are remirroring the site.

## Build, Test, and Development Commands
- `python3 -m http.server 4173` — launch a local preview from the repo root to verify relative links and service worker scope.
- `npx serve .` — alternate HTTPS-friendly preview when testing on phones.
- `git status -sb` — ensure only intentional files will ship.

## Coding Style & Naming Conventions
- Follow the existing 4-space indentation in HTML/JS snippets and keep attributes on separate lines when they exceed ~100 chars.
- Use double quotes for HTML attributes, single quotes inside inline scripts (`window.Parameters` etc.).
- Name new pages with lowercase hyphenated filenames (`wheel-alignment.html`) and align image/CSS filenames with the page name for discoverability.
- Run `python3 tools/tidy_html.py <file-or-folder>` after syncing HTTrack output to strip mirror comments and normalize indentation before review.


## Testing Guidelines
- There is no automated test suite; perform manual regression by loading the changed page via the local server in both desktop and mobile device emulation.
- Verify service worker changes by clearing the cache in DevTools and ensuring `f30f4.txt` registers without errors.
- For form updates (appointments, contact, reviews) use the staging endpoints defined in the HTML before pointing at production URLs.

## Commit & Pull Request Guidelines
- Recent history shows short imperative subjects prefixed with `commit:` (for example, `commit: Bring in index.html`) and occasional `WIP:` commits on feature branches—mirror that style.
- Keep commits scoped to one page or asset folder; reorder HTTrack artifacts in a dedicated commit if needed.
- Pull requests should include: summary of the customer-facing change, list of touched pages/assets, screenshots before/after for visual edits, and any manual test notes (browser, viewport, mock submission IDs).
- After updating any document or page, `git push` the branch to keep the remote mirror up to date.
- GitHub HTTPS access is configured via `~/.git-credentials` (credential.helper=store); future sessions can push without reauth as long as the token remains valid.

## Security & Configuration Tips
- Treat mirrored credentials and tracking IDs in the HTML as production data; never hard-code new secrets.
- Host new third-party scripts under a dedicated folder (for example `zapi.kukui.com/`) and load them via relative paths to avoid mixed-content errors.
