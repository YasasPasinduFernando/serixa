# Runtime Core (Milestone 4)

Status: **complete** (`v0.4.0`).

The Runtime Core is the extensible backbone for Forms, Routing adapters, reactive UI, plugins, DevTools, and SSR — without changing the public Widget API.

## Why each subsystem exists

| Subsystem | Responsibility | Future consumers |
|-----------|----------------|------------------|
| `WidgetContext` | Theme, locale, direction, color mode, breakpoint, env, flags | i18n, dark mode, responsive, feature flags |
| `RenderContext` | Per-render state: tree, depth, SSR/debug, html, metadata | Multi-pass render, streaming |
| `Container` | Lightweight DI (`bind` / `singleton` / `instance` / `resolve`) | Replacing static managers, plugins |
| `WidgetRegistry` + `WidgetMetadata` | Discoverable widget catalog | Visual builders, marketplace |
| `RenderPipeline` + passes | Replaceable render stages | Caching, a11y audits, diffing |
| `HtmlWriter` | HTML fragment assembly | Minify / stream writers |
| `Plugin*` + `PluginManager` | Extension contracts | Third-party widgets/themes |
| Inspector / Snapshot types | DevTools data model | IDE plugins (no UI in core) |
| Performance interfaces | Profiler contracts | CI budgets (no impl yet) |

## Pipeline

```text
BuildTree → ResolveContext → Lifecycle → ThemeResolution → Accessibility → Render(+HtmlWriter)
```

`Renderer` is the coordinator. Existing calls:

```php
(new Renderer())->render(Button::make('Save')->primary());
```

still work. Optionally inject context:

```php
$renderer = new Renderer(null, WidgetContext::defaults()->withColorMode('dark'));
```

`ThemeResolutionPass` bridges `WidgetContext::theme()` into `ThemeManager` so Milestone 1–3 widgets keep working.

## Container

```php
$container = new Container();
$container->singleton(ThemeInterface::class, fn () => new DefaultTheme());
$theme = $container->resolve(ThemeInterface::class);
```

Constructor autowiring resolves typed class parameters. No facades.

## Registry

```php
$registry = WidgetRegistry::withDefaults();
$registry->find('button')?->supportedProperties();
```

## Plugins

```php
$manager = new PluginManager($container, $registry);
$manager->register($myWidgetPlugin);
```

Contracts: `PluginInterface`, `WidgetPlugin`, `ThemePlugin`, `RuntimePlugin`.

## Inspector & performance

Interfaces / DTOs only:

- `WidgetInspector`, `TreeInspector`
- `WidgetSnapshot`, `RenderSnapshot`, `PerformanceSnapshot`
- `PerformanceProfiler`, `RenderProfiler`, `MemoryProfiler`

## Trade-offs

1. Widgets still call `ThemeManager` internally (BC). Context is the source of truth during pipeline via ThemeResolutionPass.
2. Widgets still return HTML from `render()` — HtmlWriter concatenates; a future paint-phase could stream without changing the method signature.
3. AccessibilityPass is an extension hook; widgets already emit ARIA today.

## Non-goals

Routing, HTTP, forms, ORM, auth, sessions, cookies, CLI, JS runtime, marketplace UI.
