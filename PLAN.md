# Delivery Plan

## Completed Tasks
- Mirror Site
- Publish on Client's Godaddy Account
- Get Codex working on local copy.

## TODO
### 1) Mirror Cleanup (baseline for all other work)
- Normalize indentation/whitespace across root HTML and shared JS; remove HTTrack artifacts via `python3 tools/tidy_html.py`.
- Spot-check pages in desktop & mobile using `python3 -m http.server 4173`; ensure relative asset paths and service worker scope still work.
- Keep CDN-sourced assets in their existing folders to preserve relative paths; avoid touching `hts-cache/` and `hts-log.txt`.

### 2) Owner Login & Settings (new `settings.html`)
- Choose lightweight auth path suitable for mostly static hosting (e.g., auth proxy, token-gated page, or external auth widget).
- Build login screen plus owner dashboard page with controls for:
    - Calendar provider selection and credential entry.
    - Chat provider selection (placeholder until integration is wired).
- Define storage/persistence approach for settings (secure backend, token store, or encrypted config); document the chosen method.

### 3) Appointment Scheduling Flow
- Select scheduling backend/API that accepts service selections and owner calendar choice.
- Update `appointments.html` and shared JS to post requests to the new endpoint; align success/error messaging.
- Add confirmation and follow-up copy consistent with existing customer messaging; test both desktop and mobile flows.

### 4) Chat Integration
- Evaluate static-friendly chat providers or embed strategies; confirm compatibility with service worker caching rules.
- Implement a widget loader that respects owner settings; ensure required assets cache via `f30f4.txt` without breaking offline behavior.
- Verify chat presence and basic function on homepage, services, and appointments pages.

### 5) Documentation & Incremental Delivery
- Record framework/API decisions and any new scripts added to the repo.
- Keep commits scoped per page/feature following `commit: ...` convention; note manual test steps and screenshots for PRs.
