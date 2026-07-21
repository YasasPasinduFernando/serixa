# Changelog

All notable changes to SERIXA are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [v0.5.2] — 2026-07-22

### Added
- Fluent `Padding` builder: `Padding::make()->all(4)->child(...)` / `->x()` / `->y()` / side setters
- `Scaffold` layout widget (`appBar`, `sidebar`, `body`, `footer`)
- Canonical PSR-4 namespace `Serixa\`
- Docs: `docs/NAMESPACE.md`, ADR 0006

### Changed
- Composer autoload root is `Serixa\`
- Theme maps `scaffold*` class keys for the new shell widget

### Deprecated
- Static `Padding::all()` / `Padding::x()` / … factories (use fluent `Padding::make()->…`; still work via `__callStatic`)

### Fixed
- None

## [v0.5.1] — 2026-07-21

### Added
- Exception hierarchy: `SerixaException`, `InvalidArgumentException`, `ContainerException`, `PipelineException`, `RegistryException`, `PluginException`
- Coding standards: `.editorconfig`, `.php-cs-fixer.dist.php`, `phpstan.neon.dist`
- GitHub Actions CI (PHPUnit + PHPStan + CS dry-run)
- Governance docs: `docs/API_STABILITY.md`, `docs/VERSIONING.md`, `docs/DEPRECATION.md`, `docs/CODING_STANDARDS.md`
- Architecture Decision Records under `docs/adr/`
- `@internal` markers on pipeline passes, component concerns, `HeadElement`

### Changed
- Container/pipeline/registry/plugin/theme errors use SERIXA exception types (still subclasses of SPL exceptions for BC)
- Composer scripts: `phpstan`, `cs-check`, `cs-fix`, `qa`

### Deprecated
- None (existing Component aliases unchanged)

### Fixed
- None

## [v0.5.0] — 2026-07-21

### Added
- Document engine: `Document`, `Html`, `Head`, `Body`
- Head elements: `Title`, `Meta`, `Link`, `Script`, `Style`
- `SeoManager` — title, description, keywords, canonical, robots, author, theme-color, favicon, Open Graph, Twitter Cards, JSON-LD placeholder
- Asset architecture: `AssetManager`, `AssetBundle`, `AssetManifest`, `AssetResolver` (versioning, defer, preload, prefetch — no Vite)
- `DocumentRenderer` for full HTML pages
- `DocumentInterface`; `Renderer` detects documents vs fragments
- Docs: `docs/document-engine.md`, `examples/document.php`, Document PHPUnit suite

### Changed
- `RendererInterface::render()` accepts `DocumentInterface|WidgetInterface|string` (additive)
- Roadmap: Milestone 5 marked complete

### Deprecated
- None

### Fixed
- None

## [v0.4.0] — 2026-07-21

### Added
- Runtime Core: `WidgetContext`, `RenderContext`
- Service `Container` (`bind`, `singleton`, `instance`, `resolve` + constructor autowiring)
- `WidgetRegistry` and `WidgetMetadata` with built-in widget catalog
- Replaceable `RenderPipeline` with passes: build tree, resolve context, lifecycle, theme resolution, accessibility, render
- `HtmlWriter` for HTML fragment assembly
- Plugin contracts: `PluginInterface`, `WidgetPlugin`, `ThemePlugin`, `RuntimePlugin`, `PluginManager`
- Inspector contracts and snapshots: `WidgetInspector`, `TreeInspector`, `WidgetSnapshot`, `RenderSnapshot`, `PerformanceSnapshot`
- Performance contracts: `PerformanceProfiler`, `RenderProfiler`, `MemoryProfiler`
- Docs: `docs/runtime-core.md`, `examples/runtime.php`, Runtime PHPUnit suite

### Changed
- `Renderer` is now a pipeline coordinator (public render APIs preserved)
- Roadmap renumbered upcoming milestones after Runtime Core

### Deprecated
- None

### Fixed
- None

## [v0.3.0] — 2026-07-21

### Added
- Widget vocabulary: `WidgetInterface`, abstract `Widget`, `WidgetCollection`, `WidgetFactory`, `WidgetVisitor`
- Widget tree engine: `WidgetTree`, `TreeNode`, `TreeWalker`, `TreeVisitor`, `LifecycleTreeVisitor`
- Lifecycle hooks: `beforeMount()`, `mounted()`, `beforeRender()`, `afterRender()`, `destroy()`
- Renderer integration with tree + lifecycle (`renderWidget()`, `tree()`)
- Memory-only state engine: `StateManager`, `StateInterface`, `WidgetState`, `StateBuilder`, `StateResolver`
- Design tokens: `DesignToken`, `ColorToken`, `SpacingToken`, `RadiusToken`, `ShadowToken`, `TypographyToken`
- `ThemeInterface::token()` and token composition inside `DefaultTheme`
- Event preparation: `EventInterface`, `ClickEvent`, `HoverEvent`, `FocusEvent`, `WidgetEventDispatcher`, `NullWidgetEventDispatcher`
- `AccessibilityBuilder` with ARIA helpers merged into widget attributes
- Shared `AssertsOption` concern
- Foundation PHPUnit suite and `examples/foundations.php`
- Project docs: `ROADMAP.md`, architecture overview, expanded README

### Changed
- Renderer now builds a `WidgetTree` and runs lifecycle visitors before/after paint
- `DefaultTheme` resolves visual styles via design tokens where applicable
- Package identity: Composer name `serixa/framework` (PSR-4 namespace remains `Serixa\` for BC)

### Deprecated
- Prefer `Serixa\Widget\Widget` over `Serixa\Component\Component` (alias retained)
- Prefer `WidgetInterface` over `ComponentInterface` (interface retained as extension alias)
- Prefer `Renderer::renderWidget()`; `renderComponent()` remains as alias

### Fixed
- TreeNode static/instance naming clash (`forWidget` / `forText` factories)
- Component BC alias now correctly implements `ComponentInterface`

## [v0.2.0] — 2026-07-21

### Added
- Layout widgets: Row, Column, Center, Padding, Spacer, Expanded, SizedBox, Stack, Divider
- Theme seam: ThemeInterface, DefaultTheme, ThemeManager
- Button fluent aliases: `primary()`, `secondary()`, `danger()`, `ghost()`, `small()`, `large()`, `rounded()`
- Layout documentation and tests

### Changed
- Milestone 1 widgets resolve CSS classes through ThemeManager (public APIs preserved)

## [v0.1.0] — 2026-07-21

### Added
- Initial rendering engine: Component base, Renderer, Button, Card, Text, Container
- Support utilities: HtmlEscaper, AttributeBuilder, ClassMerger
- PHPUnit suite and basic example
