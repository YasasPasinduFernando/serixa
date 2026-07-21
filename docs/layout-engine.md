# Layout engine (Milestone 2)

Status: **complete** (`v0.2.0`+).

Flutter-like layout widgets and a replaceable theme seam — built on top of Milestone 1 without breaking existing APIs.

## Layout widgets

| Widget | Role |
|--------|------|
| `Row` | Horizontal flex: justify, align, gap |
| `Column` | Vertical flex: align, gap |
| `Center` | Flex-center children |
| `Padding` | Spacing wrapper — prefer fluent `Padding::make()->x(6)->y(4)->child(...)` |
| `Spacer` | Flex grow empty space |
| `Expanded` | Flex grow around one child |
| `SizedBox` | Explicit width/height |
| `Stack` | Relative layer context (absolute children later) |
| `Divider` | Semantic `<hr>` |
| `Scaffold` | App shell slots: appBar, sidebar, body, footer |

## Flutter-style composition

```php
use Serixa\Component\Button;
use Serixa\Component\Center;
use Serixa\Component\Column;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;

$html = (new Renderer())->render(
    Center::make(
        Column::make([
            Text::make('Welcome'),
            SizedBox::height(20),
            Button::make('Continue')->primary()->large(),
        ]),
    ),
);
```

Array or variadic children are supported on layout widgets:

```php
Column::make([Text::make('A'), Text::make('B')]);
Column::make(Text::make('A'), Text::make('B'));
```

## Padding (fluent)

Preferred:

```php
Padding::make()
    ->x(6)
    ->y(4)
    ->child(Text::make('Inset'));
```

Deprecated static factories still work (`Padding::all(4)`, `Padding::x(2, $child)`, …) via `__callStatic` so instance methods can share the same names.

## Scaffold

Structural application shell (no routing/auth):

```php
use Serixa\Component\Scaffold;

Scaffold::make()
    ->appBar(Text::make('Top'))
    ->sidebar(Text::make('Nav'))
    ->body(Text::make('Page'))
    ->footer(Text::make('Foot'));
```

## Theme seam

Widgets never hard-code Tailwind. They resolve classes through:

```php
ThemeManager::current()->classes('row', ['justify' => 'between', 'gap' => 4]);
```

Convenience shortcuts (for docs and apps):

```php
ThemeManager::current()->buttonPrimary();
```

Swap themes without touching widget code:

```php
ThemeManager::set(new MyBootstrapTheme());
```

`DefaultTheme` is the Tailwind implementation. Future Material/Bootstrap themes implement `ThemeInterface`.

## SizedBox note

PHP cannot define static and instance methods with the same name. Use:

- `SizedBox::height(20)` / `SizedBox::width(32)` — factories
- `->withWidth()` / `->withHeight()` — chain the other axis

## Backward compatibility

Milestone 1 APIs remain: `variant()`, `size()`, `Container::make(...$children)`, etc. Milestone 2 only adds fluent aliases (`primary()`, `large()`, `rounded()`) and moves class maps into the theme.

## Out of scope

Routing, forms, auth, ORM, CLI, HTTP, and a `Page` widget (compose with `Center` / `Column` / `Container` instead).
