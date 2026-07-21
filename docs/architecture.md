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
ThemeManager → ThemeInterface → tokens → CSS classes
   ↓
Renderer → WidgetTree → lifecycle → HTML string
```

## SOLID mapping

| Principle | How |
|-----------|-----|
| SRP | Escaper, AttributeBuilder, Theme, TreeWalker, StateManager each own one job |
| OCP | New widgets + theme keys; themes swap without editing widgets |
| LSP | `Component` is a `Widget`; `ComponentInterface` is a `WidgetInterface` |
| ISP | Narrow contracts (`WidgetInterface`, `ThemeInterface`, `StateInterface`, event interfaces) |
| DIP | Widgets depend on `ThemeInterface` via ThemeManager; Renderer depends on `WidgetInterface` |

## Namespace note

Runtime namespace is `NovaPHP\` for backward compatibility through `v0.x`.
Product branding is **SERIXA** (`serixa/framework`). A future major may introduce `Serixa\` with aliases.

## Extension points

1. **Themes** — implement `ThemeInterface` (including `token()`)
2. **Widgets** — extend `Widget` / `Component`, register in `WidgetFactory` if needed
3. **Tree visitors** — implement `TreeVisitor` for inspection / plugins
4. **State** — use `StateManager` keys; inject a manager later via `StateManager::set()`
5. **Events** — implement `WidgetEventDispatcher` when the JS bridge arrives

## Non-goals (core)

Routing, controllers, middleware, sessions, cookies, database, full SPA runtime.
