# Changelog

All notable changes to SERIXA are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/),
and this project adheres to [Semantic Versioning](https://semver.org/).

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
- Package identity: Composer name `serixa/framework` (PSR-4 namespace remains `NovaPHP\` for BC)

### Deprecated
- Prefer `NovaPHP\Widget\Widget` over `NovaPHP\Component\Component` (alias retained)
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
