# Architecture overview

SERIXA is a PHP UI widget engine. It produces HTML fragments **and** complete HTML documents. It does not include routing, HTTP, ORM, auth, or CLI in core.

## Layers

```text
App code
   ↓
┌──────────────────────────────┐
│ Document (optional page)     │
│  SeoManager + AssetManager   │
│  Html / Head / Body          │
└──────────────┬───────────────┘
               ↓ body widgets
Widgets (Component/* — public DX)
   ↓
Renderer (coordinator)
   ├─ DocumentInterface → DocumentRenderer → full HTML
   └─ WidgetInterface   → RenderPipeline → fragment
        BuildTree → ResolveContext → Lifecycle → Theme → A11y → Render
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
| SRP | Document ≠ fragment Renderer; SeoManager ≠ AssetManager; each head element owns one tag |
| OCP | New assets/SEO fields without changing widget APIs |
| LSP | `Document` implements `DocumentInterface` |
| ISP | Narrow document/asset/SEO contracts |
| DIP | DocumentRenderer depends on pipeline/context, not concrete themes |

## Namespace note

Runtime namespace is `NovaPHP\` for backward compatibility through `v0.x`.
Product branding is **SERIXA** (`serixa/framework`).

## Extension points

1. **Themes** — implement `ThemeInterface` (including `token()`)
2. **Widgets** — extend `Widget` / `Component`, register metadata in `WidgetRegistry`
3. **Pipeline** — implement `RenderPassInterface`, compose `RenderPipeline`
4. **Plugins** — implement `WidgetPlugin` / `ThemePlugin` / `RuntimePlugin`
5. **Context** — pass `WidgetContext` into `Renderer`
6. **Documents** — `Document::make()` + assets/SEO
7. **Inspectors / profilers** — implement contracts when DevTools land

## Governance

- [API stability](API_STABILITY.md)
- [Versioning](VERSIONING.md)
- [Deprecation](DEPRECATION.md)
- [ADRs](adr/README.md)

## Non-goals (core)

Routing, controllers, middleware, sessions, cookies, database, Vite bundler, full SPA runtime, marketplace UI.
