# API stability

## Frozen surface (additive-only)

The following are stable for application code after Milestone 5:

| Area | Entry points |
|------|----------------|
| Widgets | `NovaPHP\Component\*` fluent APIs, `Widget` / `Component` |
| Rendering | `Renderer::render()`, `renderWidget()`, `renderDocument()`, `tree()` |
| Documents | `Document::make()`, SEO/asset fluent helpers |
| Theme | `ThemeManager`, `ThemeInterface`, `DefaultTheme`, tokens |
| Runtime | `WidgetContext`, `RenderContext`, `Container`, `WidgetRegistry` |
| Plugins | `PluginInterface`, `PluginManager` |
| Contracts | Interfaces under `NovaPHP\Contracts\` |

## Internal (may change)

Marked `@internal` or living under pipeline passes / concerns:

- `NovaPHP\Pipeline\Pass\*`
- `NovaPHP\Component\Concerns\*`
- `NovaPHP\Document\Element\HeadElement`
- Future paint/diff helpers

## Exception hierarchy

```text
Throwable
└── Exception
    ├── InvalidArgumentException (SPL)
    │   └── NovaPHP\Exception\InvalidArgumentException
    │       ├── RegistryException
    │       └── PluginException
    └── RuntimeException (SPL)
        └── NovaPHP\Exception\SerixaException
            ├── ContainerException
            └── PipelineException
```

Catching SPL `InvalidArgumentException` / `RuntimeException` remains valid.

## SOLID checklist

- **S** — Document ≠ fragment Renderer; Seo ≠ Assets; each pass one job
- **O** — Pipeline/plugins/themes extend without editing widgets
- **L** — Component is a Widget; Document implements DocumentInterface
- **I** — Narrow contracts (Theme, State, Plugin, RenderPass)
- **D** — Widgets depend on ThemeInterface via ThemeManager/context

## Contribution gate

Before merging:

1. `composer test`
2. `composer phpstan`
3. `composer cs-check`
4. No public API removals without deprecation entry
