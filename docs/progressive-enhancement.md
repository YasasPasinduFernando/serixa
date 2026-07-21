# Progressive Enhancement (v0.8.0)

SERIXA is **PHP-first** and **SSR-first**. Interactivity is optional.

## What SERIXA is not

- Not React / Vue / Svelte
- Not an SPA router
- Not a Virtual DOM
- Not a hydration engine
- Not a client state store

## Why no Virtual DOM

The HTML produced by PHP **is** the UI. A VDOM would:

- duplicate render logic in JavaScript;
- create hydration mismatch risk;
- force JS for pages that already work.

Instead, SERIXA ships semantic HTML and a tiny runtime that **discovers** enhancement hooks.

## Why Progressive Enhancement

1. **Forms work without JS** — native `method` / `action` / HTML5 constraints.
2. **Accessibility stays native** — focus order, keyboard, screen readers are not intercepted by default.
3. **Payload stays small** — `serixa.js` only registers listeners and plugins.
4. **Plugins remain additive** — future modules hook `Serixa.use()` without rewriting the core.

## Runtime architecture

```text
PHP widgets
  └─ render data-serixa-on-* + HTML constraints
       └─ Document assets → assets/runtime/serixa.js (defer)
            └─ boot() → bind listeners → optional Serixa.register() / CustomEvent
```

| Piece | Role |
|-------|------|
| `HasDomEvents` | Fluent `onClick` / `onSubmit` / … |
| `HasFormControl` | Native validation attributes |
| `EventRegistry` | PHP metadata map for plugins |
| `ClientRuntime` | Registers the JS asset |
| `serixa.js` | Boot + lookup + plugin hooks |

## Accessibility decisions

- Event wiring never `preventDefault()`s unless **your** registered handler does.
- `required` / `readonly` / `disabled` keep ARIA mirrors (`aria-required`, `aria-readonly`, `aria-disabled`).
- Search / forms keep semantic roles (`search`, `searchbox`, labels via `for`).
- Focus management APIs are reserved for future plugins via `Serixa.use('boot', …)`.

## Future compatibility

Plugins can:

- `Serixa.register('name', fn)` for named handlers;
- `Serixa.use('before:click', fn)` / `after:*` / `boot` hooks;
- read `EventRegistry` JSON from PHP for build-time tooling.

Still out of scope for v0.8.0: Modals, Tabs, client routing, StateManager ↔ DOM sync.
