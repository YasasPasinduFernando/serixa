# Rendering engine (Milestone 1)

Status: **complete** (ships in SERIXA from `v0.1.0`; still the base of later milestones).

This document describes the rendering engine: responsibilities, public API, and design decisions.

> Prefer the word **widget** in new code (`Serixa\Widget\Widget`). The `Component` name remains as a backward-compatible alias.

## Goals

- Flutter-like widgets expressed in PHP
- Developers use semantic props (`variant`, `size`, `tone`) — not Tailwind class strings for common UI
- Widgets return HTML strings
- Output is escaped by default, semantic, and accessible where practical
- Architecture stays modular, PSR-4 / SOLID, and testable

## Package layout (evolved)

```text
src/
  Contracts/       WidgetInterface, ComponentInterface (alias), RendererInterface, …
  Widget/          Abstract Widget
  Component/       Concrete widgets + Component alias
  Support/         HtmlEscaper, AttributeBuilder, ClassMerger
  Theme/           ThemeManager, DefaultTheme (from Milestone 2+)
  Rendering/       Renderer (tree-aware from Milestone 3)
```

## Public API

### `WidgetInterface` / `ComponentInterface`

Every widget implements `render(): string` plus lifecycle hooks (Milestone 3).

### `Widget` / `Component` (abstract)

Shared fluent API:

| Method | Purpose |
|--------|---------|
| `id(string)` | Set element `id` |
| `class(string ...)` | Escape hatch for extra CSS classes |
| `attribute()` / `attributes()` | Arbitrary HTML attributes |
| `render()` | Abstract — subclass produces markup |

Protected helpers: `buildAttributeString()`, `escape()`, `renderChild()`, `renderChildren()`.

### Concrete widgets

| Class | Element | Semantic props |
|-------|---------|----------------|
| `Text` | configurable tag (`as()`) | `size`, `weight`, `tone` |
| `Button` | `<button>` | `type`, `variant` / `primary()`, `size` / `large()`, `disabled`, `rounded()` |
| `Container` | `<div>` | `maxWidth`, `padding`, children |
| `Card` | `<article>` | `title`, `padding`, children |

### `Renderer`

Implements `RendererInterface::render(WidgetInterface|string ...$nodes): string`.

From Milestone 3, rendering goes through `WidgetTree` and lifecycle hooks. `renderComponent()` remains as an alias of `renderWidget()`.

## Security model

| Input | Handling |
|-------|----------|
| Text / Button labels | Escaped (`HtmlEscaper`) |
| Attribute values | Escaped (`AttributeBuilder`) |
| Child `string` inside Container/Card | Escaped |
| Child widgets | Rendered (escapes their own text) |
| String passed directly to `Renderer::render()` | Trusted — not escaped |

## See also

- [Layout engine](layout-engine.md)
- [Framework foundations](framework-foundations.md)
- [Architecture](architecture.md)
