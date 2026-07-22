# SERIXA v1.0.0 release checklist

Do **not** publish or push until this list is explicitly signed off.

## Quality gates

- [ ] `framework/` — `composer qa` green
- [ ] `packages/cli` — `composer qa` green
- [ ] `packages/*` (icons, admin, forms, auth-ui, charts, calendar, devtools) — `composer qa` or package tests green
- [ ] `website/` — `composer qa` green
- [ ] `starters/blog` + `starters/admin` — tests green
- [ ] Playground smoke render
- [ ] Validation apps render
- [ ] Offline verify (`tools/css/_verify-offline.php`) — no CDN
- [ ] `php tools/benchmarks/v1-performance.php` recorded

## Docs

- [ ] README accurate (packages, offline CSS, documentation index)
- [ ] FAQ / installation match Theme Compiler story
- [ ] [styling.md](styling.md), [assets.md](assets.md), [performance-v1.md](performance-v1.md)
- [ ] [MIGRATION-v1.md](MIGRATION-v1.md), [UPGRADE-v1.md](UPGRADE-v1.md), [KNOWN-ISSUES-v1.md](KNOWN-ISSUES-v1.md)
- [ ] Website DocsCatalog includes styling / assets / troubleshooting / performance

## Release artifacts (prepare only)

- [ ] CHANGELOG entry for v1.0.0
- [ ] Annotated git tag `v1.0.0` (when publishing)
- [ ] GitHub release notes draft (do not publish until approved)
- [ ] Packagist publish plan for `serixa/framework` + packages

## Explicit non-goals for this freeze

- No new packages
- No new widgets unless a P0 bug requires one
- No push / no Packagist publish from this checklist alone
