---
name: serixa-engineering
description: Develop the Serixa PHP widget framework, Composer packages, starters, CLI, or documentation website.
---

Serixa is a Flutter-inspired PHP UI framework, not a generic MVC application. `framework/` is the published `serixa/framework` library (PSR-4 `Serixa\`, PHP 8.2+ language baseline). Root composer.json is a local monorepo meta-package. Inspect nested composer.json files before modifying release metadata.

Read `README.md`, `CONTRIBUTING.md`, `SECURITY.md`, and the affected package sources/tests. `packages/` contains ecosystem packages; `starters/` scaffolds consumers; `website/` is the docs application and static exporter. Preserve semantic HTML, escaping, accessibility, progressive enhancement and backwards compatibility of public widgets.

Run framework `composer test`, `composer phpstan`, and `composer cs-check` from `framework/`. Root `composer test:packages` and `composer test:starters` check package/starter skeletons. Verify website changes with `website/bin/export-static.php` after reading its options and `website/DEPLOY.md`. Never publish PHP source as static assets.

For PHP security work read `docs/php-runtime-security.md`. Composer/library updates cannot patch the web server's PHP runtime. Verify the actual FPM/Apache/container version separately; CLI version alone is insufficient. Do not claim production remediation from a local test. Package release/tag/Packagist publication is separate from committing a monorepo change.
