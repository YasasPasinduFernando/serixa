# Architecture overview

SERIXA is a PHP UI widget engine. It produces HTML fragments. It does not include routing, HTTP, ORM, auth, or CLI in core.

## Layers

```text
App code
   ↓
Widgets (Component/* — public DX)
   ↓
Widget base + concerns (attributes, children, theme resolve, a11y, state key)
   ↓
Renderer (coordinator)
   ↓
RenderPipeline passes
   BuildTree → ResolveContext → Lifecycle → ThemeResolution → Accessibility → Render
   ↓
HtmlWriter → HTML string
```

Cross-cutting runtime services:

```text
WidgetContext / RenderContext
Container (DI)
WidgetRegistry + WidgetMetadata
PluginManager
ThemeManager (BC bridge from WidgetContext.theme)
StateManager (memory-only)
Inspector + Performance contracts
```

## SOLID mapping

| Principle | How |
|-----------|-----|
| SRP | Escaper, HtmlWriter, each pipeline pass, Container, Registry |
| OCP | New passes/plugins/widgets without editing Renderer internals |
| LSP | `Component` is a `Widget`; plugin interfaces extend `PluginInterface` |
| ISP | Narrow contracts (inspectors, profilers, plugins, passes) |
| DIP | Pipeline depends on `RenderPassInterface`; plugins boot via Container |

## Namespace note

Runtime namespace is `NovaPHP\` for backward compatibility through `v0.x`.
Product branding is **SERIXA** (`serixa/framework`).

## Extension points

1. **Themes** — implement `ThemeInterface` (including `token()`)
2. **Widgets** — extend `Widget` / `Component`, register metadata in `WidgetRegistry`
3. **Pipeline** — implement `RenderPassInterface`, compose `RenderPipeline`
4. **Plugins** — implement `WidgetPlugin` / `ThemePlugin` / `RuntimePlugin`
5. **Context** — pass `WidgetContext` into `Renderer`
6. **Inspectors / profilers** — implement contracts when DevTools land

## Non-goals (core)

Routing, controllers, middleware, sessions, cookies, database, full SPA runtime, marketplace UI.
