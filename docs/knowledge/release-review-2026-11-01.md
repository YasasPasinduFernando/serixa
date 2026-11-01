# v1.0.2 release review — 2026-11-01

Scope: SERIXA framework interactive components, runtime accessibility, theme polish, CSS visibility invariants, the public component catalog, static export, Composer distribution and Cloudflare Pages deployment. This is evidence for the defined release gates, not a guarantee that every consumer application or browser is defect-free.

## Released artifacts

- Monorepo release commit: `b288634644d7742221d654327c9ccc6d20ebd2d2`
- CI maintenance commit: `fcd13bc34ec6a930eb11befeb6e8ec3961db38cd`
- Framework mirror commit: `26aca622c989531ae2b5ae240a57011ae6b1fd89`
- Composer tag and GitHub Release: `serixa/framework` v1.0.2
- Packagist source reference: `26aca622c989531ae2b5ae240a57011ae6b1fd89`
- Cloudflare Pages project: `serixa` on the `main` production branch
- Custom domain: `https://serixa.yasaboy.com`

## Framework changes

- Modal, Drawer and Popover expose accessible fallback names and configurable `ariaLabel()` APIs.
- Dropdown and Popover put ARIA state on the focusable trigger without mutating a reusable trigger widget.
- Drawer and Modal trap focus and restore it on close; Popover closes on outside click and synchronizes `aria-expanded`.
- Progress rejects non-finite input and clamps visual and ARIA values to its declared range.
- Avatar initials remain UTF-8 safe without requiring mbstring.
- Dark-mode, focus-visible and reduced-motion defaults cover interactive and feedback components.
- The CSS foundation enforces the `hidden` invariant so utility display classes cannot flash closed overlays before runtime initialization.

## Website and deployment changes

- Added a living catalog rendered from real SERIXA components.
- Fixed narrow-screen component sizing and content overlap.
- Static export rewrites internal links and canonical/OG/JSON-LD URLs to path-style routes.
- Every docs page has one document-level heading and its own canonical URL.
- Sitemap contains all 34 exported routes.
- Cloudflare headers include CSP, HSTS, COOP, frame denial, MIME sniffing protection, restrictive permissions policy and asset caching.
- Root workflows use `actions/checkout@v7`; PHP 8.2 and 8.3 CI passed.

## Verification evidence

- Framework: 273 PHPUnit tests / 3,032 assertions; PHPStan and PHP-CS-Fixer passed.
- Website: 43 PHPUnit tests / 331 assertions; PHPStan and PHP-CS-Fixer passed.
- PHP 8.2 syntax: 250 files passed.
- Package and starter contracts: 69 packages and 6 starters passed.
- Composer manifests validated strictly; locked dependency audits reported no advisories.
- Static export: 34 pages, 1,338 internal asset/navigation links, zero missing, 34/34 canonical URLs correct.
- Browser matrix: 34 pages at 390, 768 and 1,440 px (102 navigations), with zero horizontal page overflow, unlabeled controls, visible closed overlays or JavaScript exceptions.
- Component runtime: 12 modal, drawer, dropdown, popover, tabs, toast and alert interaction checks passed.
- Live custom domain: responsive and interaction checks passed at 390 and 1,440 px with zero JavaScript exceptions.
- Bugbot review found three medium findings; all were fixed and covered by regression checks. Security review found no issues.
- A clean external consumer installed `serixa/framework` v1.0.2 from Packagist successfully.

## Consumer update behavior

Composer does not silently rewrite an existing application's lock file. Consumers whose constraint permits the release, for example `^1.0`, update with:

```bash
composer update serixa/framework
```

New consumers can install the release with `composer require serixa/framework:^1.0.2`.

## Remaining operational boundaries

- The GitHub-hosted runner currently emits an informational notice about the planned `ubuntu-latest` migration to Ubuntu 26. Jobs pass; pin a runner image only if a future migration changes behavior.
- Optional ecosystem packages passed structural contract checks, not full independent release certification.
- Production application PHP/FPM versions remain the responsibility of each consumer host.
- The CSP permits inline scripts/styles because the current document engine emits JSON-LD, a small copy helper and inline progress widths. Removing those allowances requires nonce/hash support or externalizing all inline output.
