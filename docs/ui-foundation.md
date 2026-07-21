# UI Foundation (Milestone 6.0)

Status: **complete** (`v0.6.0`).

Essential primitives for real pages: app chrome, forms, links, and field messaging. Render-only — no validation, routing, or JavaScript.

## Architecture

```text
Scaffold (landmarks)
  ├─ header → AppBar
  ├─ aside  → Sidebar (nav)
  ├─ body   → MainContent (<main>)
  └─ footer → Footer

Form (<form>)
  └─ FieldGroup
       ├─ Label
       ├─ Input | PasswordInput | Textarea | Select | Checkbox | Radio | SwitchControl
       ├─ HelperText
       └─ ErrorText

Link — first-class <a>
Divider — orientation + spacing
```

All widgets:

- Resolve styles through `ThemeInterface` / design tokens
- Register in `WidgetRegistry`
- Compose via `WidgetInterface` + render pipeline
- Stay free of submit handlers, validators, and client scripts

## Core layout

```php
Scaffold::make()
    ->appBar(AppBar::make()->title('Dashboard'))
    ->sidebar(Sidebar::make(Link::make('Home')->href('/')->active()))
    ->body(MainContent::make(Text::make('Hello')->as('h1')))
    ->footer(Footer::make('© 2026 SERIXA'));
```

`Scaffold` owns landmarks (`<header>`, `<aside>`, `<footer>`). `AppBar` / `Sidebar` / `Footer` supply inner content so landmarks are not doubled. `MainContent` always renders `<main>`.

## Forms

```php
Form::make()
    ->method('POST')
    ->action('/login')
    ->child(
        Column::make([
            Label::make('Email')->for('email'),
            Input::make()->id('email')->name('email')->type('email'),
            PasswordInput::make()->name('password'),
            Checkbox::make()->name('remember')->label('Remember me'),
            Button::make('Login')->primary()->type('submit'),
        ]),
    );
```

### Switch naming

PHP reserves the keyword `switch`, so the widget class is **`SwitchControl`**. Registry name remains `switch`.

```php
SwitchControl::make()->name('dark')->label('Dark mode')->checked();
```

### Accessibility

| Pattern | Approach |
|---------|----------|
| Labels | `Label::for($id)` + control `id()` |
| Help / errors | `HelperText` / `ErrorText` + `describedBy('help-id error-id')` |
| Invalid UI | `->invalid()` sets `aria-invalid` (no validator) |
| Switch | `role="switch"` + `aria-checked` |
| Errors | `ErrorText` uses `role="alert"` |
| Active link | `Link::active()` → `aria-current="page"` |

## Out of scope (Milestone 6.1+)

Validation engines, form submission handling, routing, modals, toasts, tables, charts, reactivity, Avatar, DataTable, Pagination.
