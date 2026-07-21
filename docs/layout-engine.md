# Layout engine (Milestone 2)

Status: **complete** (`v0.2.0`+).

Flutter-like layout widgets and a replaceable theme seam — built on top of Milestone 1 without breaking existing APIs.

## Layout widgets

| Widget | Role |
|--------|------|
| `Row` | Horizontal flex: justify, align, gap |
| `Column` | Vertical flex: align, gap |
| `Center` | Flex-center children |
| `Padding` | Spacing wrapper (`all` / `x` / `y` / sides) |
| `Spacer` | Flex grow empty space |
| `Expanded` | Flex grow around one child |
| `SizedBox` | Explicit width/height |
| `Stack` | Relative layer context (absolute children later) |
| `Divider` | Semantic `<hr>` |

## Flutter-style composition

```php
use NovaPHP\Component\Button;
use NovaPHP\Component\Center;
use NovaPHP\Component\Column;
use NovaPHP\Component\SizedBox;
use NovaPHP\Component\Text;
use NovaPHP\Rendering\Renderer;

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
