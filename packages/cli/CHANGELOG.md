# Changelog

## [0.2.0] — 2026-07-23

### Added
- Official `serixa new` presets: `admin`, `blog`, `ecommerce`, `crm`, `cms`, `saas`
- Preset feature pages: Dashboard, Posts, Catalog, Contacts, Articles, Billing
- Dynamic `?page=` routing in generated `public/index.php` for any `App\Pages\*Page`

### Fixed
- Project title separator encoding in generated README/home titles

## [0.1.3] — 2026-07-22

### Added
- `serixa profile` — cold/warm timings for ThemeCompiler, ClassMerger, Renderer, Document
- `serixa cache:clear` — clear in-process ClassMerger / ThemeCompiler / ThemeManager / RenderPipeline caches
- Help examples for `doctor`, `benchmark`, `profile`, `inspect`, `cache:clear`

## [0.1.2] — 2026-07-22

### Added
- `serixa doctor` — environment / asset health checks
- `serixa benchmark` — ThemeCompiler / StyleCompiler / Renderer samples
- `serixa inspect` — cwd, stylesheet URL, theme sample

## [0.1.1] — 2026-07-22

### Changed
- `serixa build` compiles offline CSS via `StyleSheet::build()`, copies runtime JS, writes asset manifests, and prints a build report
- New project layout stub registers `StyleSheet` instead of Tailwind CDN
- Project template includes `resources/css/app.css`

## [0.1.0] — 2026-07-22

### Added
- Initial `serixa/cli` package extracted from `serixa/framework`
- Commands: `new`, `make:*`, `docs`, `build`, `dev`
- Project and make stubs under `templates/`
- Namespace `Serixa\Cli\`
