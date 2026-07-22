# Changelog

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
