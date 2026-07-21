# Events (v0.8.0)

SERIXA events are **progressive enhancement metadata**, not a client framework.

## Fluent API

```php
Button::make('Save')->onClick('saveForm');

Input::make()->name('q')->onInput('search')->onChange('filter');

Form::make()->method('POST')->action('/login')->onSubmit('login');
```

Supported methods:

| Method | DOM event | Attribute |
|--------|-----------|-----------|
| `onClick()` | `click` | `data-serixa-on-click` |
| `onChange()` | `change` | `data-serixa-on-change` |
| `onInput()` | `input` | `data-serixa-on-input` |
| `onSubmit()` | `submit` | `data-serixa-on-submit` |
| `onFocus()` | `focus` | `data-serixa-on-focus` |
| `onBlur()` | `blur` | `data-serixa-on-blur` |
| `onKeyDown()` | `keydown` | `data-serixa-on-keydown` |
| `onKeyUp()` | `keyup` | `data-serixa-on-keyup` |

Handler names are opaque identifiers (safe characters only). PHP does **not** execute them.

## What the server does

1. Widgets emit `data-serixa-on-*` attributes.
2. Optional `EventRegistry` stores name → event metadata for plugins/tooling.
3. No server-side event dispatch for browser DOM events.

## What the client does

`assets/runtime/serixa.js` boots after DOM ready, finds decorated elements, and:

- calls `Serixa.register(name, fn)` handlers when present;
- otherwise dispatches `CustomEvent('serixa:' + name)` and **leaves native behavior alone**.

```js
Serixa.register('saveForm', function (event, el) {
  // optional enhancement — never required for the page to work
});
```

## HTML validation (not an engine)

```php
Input::make()
    ->required()
    ->readonly()
    ->min(1)
    ->max(100)
    ->minLength(6)
    ->maxLength(255)
    ->pattern('[A-Za-z]+')
    ->autocomplete('email');
```

These map to native HTML attributes (`required`, `minlength`, …) plus matching ARIA where useful (`aria-required`, `aria-readonly`). The browser owns constraint validation unless you add your own engine later.
