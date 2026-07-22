# Changelog

All notable changes to `serixa/devtools` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [0.1.0] - 2026-07-22

### Added

- Initial release of official SERIXA DevTools (development only).
- Facade: `DevTools::enable|disable|collect|panel|overlay|attach|injectHtml|profile|registerCli`.
- Collectors: render time, memory, widget count, theme, assets, events, PE, accessibility, CLI.
- Inspectors: widget/render/component/theme/document/asset/plugin trees; framework Tree/Widget inspector implementations.
- Panels: Overview, Widgets, Themes, Performance, Accessibility, Assets, Events, Runtime, Console, Plugins.
- Overlay + `assets/devtools.js` / `devtools.css` (Ctrl+Shift+D, Alt inspect).
- CLI: `inspect`, `profile`, `doctor`, `benchmark` via `CliRegistrar`.
- `DevToolsTheme` / `devtools.*` theme keys.
- Examples: demo, disabled.
- Documentation in `docs/devtools.md`.
