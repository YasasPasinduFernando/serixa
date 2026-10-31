# v1.0.1 release review — 2026-10-31

Scope: framework Composer distribution, HTML attribute rendering, default text themes, docs responsive styling and dependency metadata. This targeted review does not prove absence of all vulnerabilities across ecosystem packages or production infrastructure.

## Fixed

- AttributeBag escaped attribute names but allowed whitespace that could introduce another attribute. Strict name syntax now rejects malformed names.
- Attribute escaping did not prevent executable URL schemes. Script schemes are rejected for href/src/action/formaction/xlink:href; data navigation is rejected except src. These controls are not an HTML sanitizer or a general allowlist for untrusted iframe/image sources. Developers must still validate allowed URL destinations and avoid raw HTML/event attributes from untrusted inputs.
- DefaultTheme text rendering discarded dark-mode token classes. All token classes are preserved; default/muted text now remains readable on dark backgrounds.
- Docs mobile navigation remains available, article content can shrink without page overflow, image sizing and heading scroll offsets are improved, and reduced-motion preferences are respected.

## Verification

PHP 8.2.35 container: 272 PHPUnit tests / 2890 assertions, PHPStan level 6 and PHP-CS-Fixer passed. New security and theme regression tests failed before the fixes and passed after. Composer strict validation passed; locked dependency audit reported no advisories. This is a dependency-advisory snapshot, not a security guarantee.

Static export produced 33 pages. Chromium checks at 390px and 1280px in both themes showed correct theme state, no horizontal document overflow and no JavaScript page errors on the homepage. Dark-mode rendering was inspected. Browser coverage is sampled, not every component/browser combination.

## Remaining work and boundaries

- The docs changes are committed/build-verified, not deployed to Cloudflare by the Composer release.
- Production PHP/FPM/Apache versions remain unverified. The test container was updated from 8.2.34 to 8.2.35; that does not upgrade a hosted application.
- Mirror sync is not a QA gate. Protect main and require CI before merging; stable versions must only be tagged after release verification.
- Ecosystem skeleton checks are not full integration tests. Each optional package needs its own release review.
- Browser runtime modal/drawer focus trapping and repeated boot behavior warrant a dedicated interaction suite; no comprehensive accessibility certification is claimed.
- Raw HTML, arbitrary developer-supplied event attributes and external resource URLs remain trusted-code APIs. Do not pass unsanitized user maps to them.
- PHP 8.2 reaches end of upstream support on 2026-12-31; plan supported-runtime CI and hosting upgrades.
