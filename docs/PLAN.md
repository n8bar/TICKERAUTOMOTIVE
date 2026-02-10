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
- [x] 5) Owner Login & Settings (new `settings.html`)
    - [x] Choose lightweight auth path suitable for mostly static hosting (e.g., auth proxy, token-gated page, or external auth widget).
    - [x] Build login screen plus owner dashboard page.
    - [x] Use `/owner/login` with no public nav link; add `noindex` guidance when implemented.
    - [x] Define storage/persistence approach for settings (secure backend, token store, or encrypted config); document the chosen method.
    - [x] Decide dev stack to match production (IIS vs Linux/Apache/Nginx) to avoid environment-specific issues (e.g., IIS 403.14 on `/owner/login` when PHP/default docs aren’t configured).
    - [x] Add User Settings UI so owners can add/remove admin accounts.
    - [x] Require strong passwords (OTP deferred for now).
        - [x] Enforce minimum 12 characters with 3-of-4 character classes.
        - [x] Add “Generate strong password” helper in the change password UI.
        - [x] Include show/hide + copy UX for generated passwords.
    - [x] Expand user controls to support creating/editing accounts, access-based visibility, and login email changes.
- [x] 6) Contact Forms
    - [x] Build new contact forms in `appointments.php` and `contact-us.php` (no existing forms to inventory).
        - [x] Add base form markup with labels, inputs, and submit actions for each page.
        - [x] Match field order to settings schema (contact: name, phone, email, vehicle, message; appointment adds service/vehicle details/preferred time).
        - [x] Add inline helper text where needed (privacy note, required indicator).
    - [x] Forms settings UI
        - [x] Add form sections and field toggles in owner settings (per-form recipients + optional auto-reply).
        - [x] Align settings fields with page schema and ensure hidden fields are not required.
        - [x] Add a delivery override toggle in the Forms tab to route submissions to a developer email for debugging (restricted access).
    - [x] Submission handling (SMTP)
        - [x] Choose SMTP service provider (Mailgun US) and confirm credentials.
        - [x] Implement SMTP delivery for form submissions.
        - [x] Add SMTP configuration fields in the settings file but keep them hidden in the UI for now (edit settings directly until client requests UI exposure).
        - [x] Wire settings to frontend forms (field visibility + required flags).
        - [x] Add validation for required fields, email format, and phone normalization on submit.
        - [x] Add success + error states (inline, accessible, and non-blocking).
        - [x] Add spam controls (honeypot + time-to-submit guard) without breaking current UX.
        - [x] Ensure submissions include page/source metadata (page name, timestamp, user agent).
        - [x] Add lightweight email template for admin notifications (subject + body summary).
    - [x] Verification
        - [x] Verify service worker caching does not block form POSTs.
        - [x] Manual regression: desktop + mobile emulation and a mock submission for each form.

## TODO
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

### 10) Dev Environment
- Keep a tracked `.env.example` in sync with local `.env` whenever Docker-related settings change.
