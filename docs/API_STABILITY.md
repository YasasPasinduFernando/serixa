# API stability

## Frozen surface (additive-only)

The following are stable for application code after Milestone 5:

| Area | Entry points |
|------|----------------|
| Widgets | `Serixa\Component\*` fluent APIs, `Widget` / `Component` |
| Rendering | `Renderer::render()`, `renderWidget()`, `renderDocument()`, `tree()` |
| Documents | `Document::make()`, SEO/asset fluent helpers |
| Theme | `ThemeManager`, `ThemeInterface`, `DefaultTheme`, tokens |
| Runtime | `WidgetContext`, `RenderContext`, `Container`, `WidgetRegistry` |
| Plugins | `PluginInterface`, `PluginManager` |
| Contracts | Interfaces under `Serixa\Contracts\` |

## Internal (may change)

Marked `@internal` or living under pipeline passes / concerns:

- `Serixa\Pipeline\Pass\*`
- `Serixa\Component\Concerns\*`
- `Serixa\Document\Element\HeadElement`
- Future paint/diff helpers

## Exception hierarchy

```text
Throwable
â””â”€â”€ Exception
    â”œâ”€â”€ InvalidArgumentException (SPL)
    â”‚   â””â”€â”€ Serixa\Exception\InvalidArgumentException
    â”‚       â”œâ”€â”€ RegistryException
    â”‚       â””â”€â”€ PluginException
    â””â”€â”€ RuntimeException (SPL)
        â””â”€â”€ Serixa\Exception\SerixaException
            â”œâ”€â”€ ContainerException
            â””â”€â”€ PipelineException
```

Catching SPL `InvalidArgumentException` / `RuntimeException` remains valid.

## SOLID checklist

- **S** — Document â‰  fragment Renderer; Seo â‰  Assets; each pass one job
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
