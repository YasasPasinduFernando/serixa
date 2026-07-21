# Framework foundations (Milestone 3)

Status: **complete** (`v0.3.0`).

Architectural foundations for scaling SERIXA to hundreds of widgets — without routing, forms, HTTP, or ORM.

## Why these pieces exist

| Concern | Why |
|---------|-----|
| **Widget vocabulary** | Flutter-aligned naming; `Component` remains as a BC alias |
| **WidgetTree** | Separates structure from paint — enables future diffing, DevTools, hot reload |
| **Lifecycle** | Stable hooks for plugins (`beforeMount` â†’ `destroy`) without changing widget APIs |
| **StateManager** | Memory-only state for Modal/Tabs/Accordion later — no sessions/cookies/DB |
| **Design tokens** | Widgets/themes speak semantic tokens; CSS frameworks stay swappable |
| **Events (interfaces)** | Reserve contracts now; browser wiring later without a second rewrite |
| **AccessibilityBuilder** | Central ARIA helpers; widgets stay semantic by default |

## Backward compatibility

| Old API | New API | Status |
|---------|---------|--------|
| `Serixa\Component\Component` | `Serixa\Widget\Widget` | Alias — still works |
| `ComponentInterface` | `WidgetInterface` | Extends — still works |
| `Renderer::renderComponent()` | `renderWidget()` | Both work |
| `ThemeInterface::classes()` | + `token()` | Additive |

Existing `Button::make()->primary()` code requires **no** changes.

## Widget tree pipeline

```text
Widget â†’ WidgetTree::from() â†’ TreeWalker + LifecycleVisitor â†’ widget.render()
```

Lifecycle order per render:

1. `beforeMount` / `mounted` (pre-order)
2. `beforeRender` (pre-order)
3. `render()` on the root (children still compose inside widgets)
4. `afterRender` (post-order)

`destroy()` is available for explicit teardown; the renderer does not auto-destroy after every paint.

## State (memory only)

```php
StateManager::current()->get('modal')->set('open', true);

Button::make('Toggle')->stateKey('modal');
```

`StateBuilder`, `WidgetState`, `StateResolver` support fluent setup and injection later.

## Design tokens

```php
use Serixa\Token\ColorToken;
use Serixa\Theme\ThemeManager;

ThemeManager::current()->token(ColorToken::primary());
// â†’ ['bg-sky-600', 'hover:bg-sky-700']
```

Token types: `ColorToken`, `SpacingToken`, `RadiusToken`, `ShadowToken`, `TypographyToken`.

`DefaultTheme::classes()` composes tokens internally so widget output stays stable.

## Events (preparation)

Interfaces/classes only: `EventInterface`, `ClickEvent`, `HoverEvent`, `FocusEvent`, `WidgetEventDispatcher`, `NullWidgetEventDispatcher`.

No DOM binding in this milestone.

## Accessibility

```php
$button->accessibility()->role('button')->label('Save')->expanded(false);
```

Attributes merge into `buildAttributeString()` when the builder is used.

## Factory & collection

```php
$factory = new WidgetFactory();
$factory->make('button', 'Save');

new WidgetCollection([Text::make('A'), Button::make('B')]);
```

## Out of scope

Routing, forms, auth, HTTP, ORM, CLI, `App::build`, browser event wiring.
