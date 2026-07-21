# Runtime modules (v0.9.0)

Status: **complete**.

SERIXA’s client script (`assets/runtime/serixa.js`) is a **progressive-enhancement** runtime. It discovers SSR hooks and mounts small modules. It is **not** a framework UI layer.

## What this is not

- Not a Virtual DOM
- Not a hydration engine
- Not an SPA router
- Not a client state store

PHP HTML **is** the UI. Modules only open/close, trap focus, and handle keyboard where markup already exists.

## Boot sequence

```text
Document loads serixa.js (defer)
  └─ DOMContentLoaded (or immediate if already ready)
       └─ Serixa.boot(document)
            ├─ bind data-serixa-on-* event metadata (v0.8)
            ├─ bootModules() → each registered module.mount(root)
            └─ runPlugins('boot', { root, modules })
```

```js
// Public surface (simplified)
Serixa.version;          // '0.9.0'
Serixa.module(name, { mount(root) { … } });
Serixa.getModule(name);
Serixa.modules();        // registered names
Serixa.boot(root);       // re-run after dynamic HTML insertion
Serixa.register / get / use; // v0.8 handler + plugin hooks
```

`ClientRuntime::register($assets)` adds the deferred script to the document. Host apps must serve the file at the chosen public URL.

## Serixa.module

Modules register at script load time:

```js
module('modal', {
  mount: function (root) {
    root.querySelectorAll('[data-serixa-modal]').forEach(/* … */);
  }
});
```

Each module:

1. Queries SSR `data-serixa-*` roots under `root`
2. Guards with `data-serixa-module-bound` to avoid double-binding
3. Attaches listeners only — it does not re-render trees

## Built-in modules

| Module key | PHP widget(s) | Responsibility |
|------------|---------------|----------------|
| `modal` (**ModalModule**) | `Modal` | Open/close, backdrop, Escape, focus trap / restore |
| `drawer` | `Drawer` | Same overlay patterns for side panels |
| `dropdown` (**DropdownModule**) | `Dropdown` | Toggle menu, Escape, arrow / Home / End on `menuitem`s |
| `popover` | `Popover` | Toggle anchored panel, outside click / Escape |
| `tooltip` (**TooltipModule**) | `Tooltip` | Show/hide tip on focus / hover / Escape |
| `tabs` (**TabsModule**) | `Tabs` | Activate tab, sync panels, arrow-key roving tabindex |
| `accordion` (**AccordionModule**) | `Accordion` | Expand/collapse; respects `data-serixa-multiple` |
| `toast` (**ToastModule**) | `Toast` | Visibility helpers for live-region toasts |
| `dismiss` | Alert / Chip dismiss buttons | Click `[data-serixa-*-dismiss]` to hide host |

### ModalModule

Targets `[data-serixa-modal]`. Reads `data-serixa-open` / `data-serixa-dismissible`. Dialog node: `[data-serixa-modal-dialog]`. Close control: `[data-serixa-close]`.

### DropdownModule

Targets `[data-serixa-dropdown]`. Trigger: `[data-serixa-dropdown-trigger]` (`aria-expanded`). Panel: `[data-serixa-dropdown-panel]` (`role="menu"`). Items: `[data-serixa-dropdown-item]`.

### TabsModule

Targets `[data-serixa-tabs]`. List `[data-serixa-tablist]`, tabs `[data-serixa-tab]`, panels `[data-serixa-tabpanel]`. Keeps `aria-selected` / `hidden` in sync with SSR ids.

### AccordionModule

Targets `[data-serixa-accordion]`. Triggers `[data-serixa-accordion-trigger]` toggle `[data-serixa-accordion-panel]` via `aria-expanded` / `aria-controls`.

### TooltipModule

Targets `[data-serixa-tooltip]`. Shows `[data-serixa-tooltip-content]` (`role="tooltip"`) referenced by `aria-describedby` on the trigger wrapper.

### ToastModule

Targets `[data-serixa-toast]` (`role="status"`, `aria-live`). Manages open/hidden without replacing live-region semantics.

## Overlay stack

Open overlays push onto an internal stack so Escape dismisses the topmost dismissible overlay. Exposed as `Serixa.overlayStack()` for debugging only.

## PHP registration

```php
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

$document = Document::make()->title('App')->body($page);
ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');
```

`ClientRuntime::scriptPath()` resolves the shipped filesystem path for tests and packaging.

## Design rules

1. **SSR first** — closed modals ship with `hidden`; open tabs ship with `aria-selected`.
2. **No VDOM** — modules mutate attributes / visibility on existing nodes only.
3. **No business logic** — handlers stay in `Serixa.register()` or host scripts.
4. **Idempotent mount** — safe to call `Serixa.boot()` after partial HTML inserts.
5. **Additive** — apps can `Serixa.module('custom', { mount })` before or after boot.

## Compatibility with v0.8

Event metadata (`data-serixa-on-click`, …), `Serixa.register`, and `Serixa.use` remain unchanged. Interactive modules share the same boot pass.
