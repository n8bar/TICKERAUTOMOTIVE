# Delivery Plan

## Completed Tasks
- [x] Mirror Site
- [x] Publish on Client's Godaddy Account
- [x] Get Codex working on local copy.

## TODO
### 1) Mirror Cleanup (baseline for all other work)
- [x] Normalize indentation/whitespace across root HTML and shared JS; remove HTTrack artifacts via `python3 tools/tidy_html.py`.
- [x] Spot-check pages in desktop & mobile using `python3 -m http.server 4173`; ensure relative asset paths and service worker scope still work.
- Keep CDN-sourced assets in their existing folders to preserve relative paths; avoid touching `hts-cache/` and `hts-log.txt`.
- DOM Cleanup Strategy (preserve UX while simplifying markup):
  - Inventory: build a selector/JS reference map and a do-not-touch list (any `id`, `class`, `data-*`, `role`, `aria-*`, inline `style`, and all `dm*` grid/runtime hooks).
  - Safe removal rules: remove empty elements and single-child wrappers only when they have **meaningless attributes** (empty/default attributes not referenced by CSS/JS) and no selector depends on the wrapper chain.
  - Preserve placeholders: keep runtime `<style>` placeholders and any nodes populated by JS at runtime.
  - Definition of “meat and potatoes”: elements that carry layout/behavior styling (referenced by CSS/JS) or meaningful content; remove wrapper-only elements with **meaningless attributes**.
  - UX constraint: preserving the look and feel means **do not change the UX at all** (layout, interactions, copy, flows).
  - [x] Pilot: apply the cleanup to `about-us.html` first; verify visually (desktop + mobile) before scaling to other pages.

### 2) Reviews & Ratings (Homepage + Reviews Pages)
- Replace static home page reviews with the latest 5-star Google and Yelp reviews.
- Rework the `reviews` page to reflect the same reviews/sources.
- [x] Update `leave-a-review` flow to point to the correct review destinations.

### 3) Owner Login & Settings (new `settings.html`)
- Choose lightweight auth path suitable for mostly static hosting (e.g., auth proxy, token-gated page, or external auth widget).
- Build login screen plus owner dashboard page with controls for:
    - Calendar provider selection and credential entry.
    - Chat provider selection (placeholder until integration is wired).
- Define storage/persistence approach for settings (secure backend, token store, or encrypted config); document the chosen method.

### 4) Appointment Scheduling (TBD)
- [x] Keep appointment placeholders for now while owner decides on the scheduling system.
- Revisit scheduling integration after settings and admin decisions are finalized.

### 5) Chat Integration
- Evaluate static-friendly chat providers or embed strategies; confirm compatibility with service worker caching rules.
- Implement a widget loader that respects owner settings; ensure required assets cache via `f30f4.txt` without breaking offline behavior.
- Verify chat presence and basic function on homepage, services, and appointments pages.
