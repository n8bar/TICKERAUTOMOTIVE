# Delivery Plan

## Completed Tasks
- [x] 1) Mirror Site
- [x] 2) Publish on Client's Godaddy Account
- [x] 3) Get Codex working on local copy.
- [x] 4) Mirror Cleanup (baseline for all other work)
    - [x] Normalize indentation/whitespace across root HTML and shared JS; remove HTTrack artifacts via `python3 tools/tidy_html.py`.
    - [x] Spot-check pages in desktop & mobile using `python3 -m http.server 4173`; ensure relative asset paths and service worker scope still work.
    - [x] Keep CDN-sourced assets in their existing folders to preserve relative paths; avoid touching `hts-cache/` and `hts-log.txt`.
    - DOM Cleanup Strategy (preserve UX while simplifying markup):
      - Inventory: build a selector/JS reference map and a do-not-touch list (any `id`, `class`, `data-*`, `role`, `aria-*`, inline `style`, and all `dm*` grid/runtime hooks).
      - Safe removal rules: remove empty elements and single-child wrappers only when they have **meaningless attributes** (empty/default attributes not referenced by CSS/JS) and no selector depends on the wrapper chain.
      - Preserve placeholders: keep runtime `<style>` placeholders and any nodes populated by JS at runtime.
      - Definition of “meat and potatoes”: elements that carry layout/behavior styling (referenced by CSS/JS) or meaningful content; remove wrapper-only elements with **meaningless attributes**.
      - UX constraint: preserving the look and feel means **do not change the UX at all** (layout, interactions, copy, flows).
      - [x] Pilot: apply the cleanup to `about-us.html` first; verify visually (desktop + mobile) before scaling to other pages.

## TODO
### 5) Owner Login & Settings (new `settings.html`)
- Choose lightweight auth path suitable for mostly static hosting (e.g., auth proxy, token-gated page, or external auth widget).
- Build login screen plus owner dashboard page.
- Use `/owner/login` with no public nav link; add `noindex` guidance when implemented.
- Include optional OTP (toggleable) for admin login.
- Add SMTP setup (GoDaddy email or equivalent) for OTP delivery; keep OTP disabled until SMTP is configured.
- Define storage/persistence approach for settings (secure backend, token store, or encrypted config); document the chosen method.

### 6) Contact Forms
- Add contact forms to `appointments.html` (Schedule an Appointment) and `contact-us.html`.
- Add settings page controls for form fields and the destination email address.
- Hold a placeholder note for any additional form location if needed later.

### 7) Reviews & Ratings (Homepage + Reviews Pages)
- Replace static home page reviews with the latest 5-star Google and Yelp reviews.
- Rework the `reviews` page to reflect the same reviews/sources.
- [x] Update `leave-a-review` flow to point to the correct review destinations.

### 8) Appointment Scheduling (TBD)
- [x] Keep appointment placeholders for now while owner decides on the scheduling system.
- Revisit scheduling integration after settings and admin decisions are finalized.
- Add calendar provider selection and credential entry in `settings.html` once the provider is chosen.

### 9) Chat Integration
- Evaluate static-friendly chat providers or embed strategies; confirm compatibility with service worker caching rules.
- Implement a widget loader that respects owner settings; ensure required assets cache via `f30f4.txt` without breaking offline behavior.
- Verify chat presence and basic function on homepage, services, and appointments pages.
- Add chat provider selection (placeholder until integration is wired) in `settings.html`.
