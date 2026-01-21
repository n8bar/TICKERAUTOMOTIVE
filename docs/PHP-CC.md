# PHP Conversion Checklist

## Goals
- Convert each page to PHP with shared header/footer includes.
- Keep UX and visual look identical while removing Duda/HTTrack bloat.
- Move long inline JS/CSS into external files named after the page.

## Global Prep (one-time)
- [ ] Keep buttons consistent:
      - Pink buttons use `var(--color_2)`.
      - Button font: `Inter` with `font-weight: 600`.
      - Button height: `50px` (unless otherwise specified).
- [ ] Keep a redirect stub for each converted page:
      - `page.html` → meta refresh + JS redirect to `page.php`.

## Per-Page Conversion Steps
- [ ] **Backup** the original HTML:
      - Save `page-backup.html` for visual diffing.
- [ ] **Create PHP shell**:
      - Copy `page.html` → `page.php`.
      - Replace `<header>...</header>` with:
        `<?php include __DIR__ . '/includes/site-header.php'; ?>`
      - Replace `<footer>...</footer>` with:
        `<?php include __DIR__ . '/includes/site-footer.php'; ?>`
- [ ] **Move long inline JS** (5+ lines):
      - Create `page.js`.
      - Move inline scripts into `page.js`.
      - Keep short 1–5 line scripts inline only if truly page-specific.
- [ ] **Move large inline CSS**:
      - Create `page.css`.
      - Move large `<style>` blocks into `page.css`.
- [ ] **Keep existing external assets**:
      - Do not break CDN paths or relative links.
- [ ] **Trim Duda/HTTrack bloat**:
      - Remove unused wrappers, duplicate divs, and meaningless attributes.
      - Keep only structure needed for layout and styling.
- [ ] **Check header/footer removal**:
      - No leftover top-bar review summary in header.
      - Header/nav/footer match the shared includes exactly.
- [ ] **Verify navigation**:
      - Dropdown menus stay open on hover.
      - Submenus use pink background with white text.
- [ ] **Check hero + logo behavior**:
      - Slide logo: max-height 120px, proportional scaling.
      - Mobile order: logo appears above hero text.
- [ ] **Preview**:
      - Load `page.php` locally and compare to backup.
      - Ensure layout, spacing, colors match the original.

## About-Us Pilot Notes (applied patterns)
- Header/footer extracted into includes; page uses PHP includes.
- `about-us.html` now redirects to `about-us.php`.
- Removed Duda runtime assets and bloat.
- Moved custom JS into `about-us.js`.
- Moved custom CSS into `about-us.css`.
- Adjusted nav dropdown colors + hover behavior.
- Updated footer social icons and credit.

## Post-Conversion
- [ ] Update `docs/PLAN.md` checkmarks for completed items.
- [ ] Manual regression check in desktop + mobile emulation.
- [ ] Commit per-page changes in small, scoped commits.
