# Interactive components (v0.9.0)

Status: **complete**.

SSR-first overlay and feedback widgets. Markup is always valid without JavaScript; optional modules in `assets/runtime/serixa.js` enhance focus, keyboard, and open/close behavior. See [runtime-modules.md](runtime-modules.md).

## Architecture

```text
Modal / Drawer / Popover / Tooltip  →  overlays (data-serixa-* hooks)
Dropdown / Tabs / Accordion         →  disclosure / navigation
Toast / Alert / Badge / Chip        →  feedback
Spinner / Progress / Skeleton       →  loading affordances
ClientRuntime                       →  registers serixa.js (defer)
```

All widgets resolve Tailwind via `ThemeInterface`, register in `WidgetRegistry`, and compose through the existing render pipeline. There is **no** client VDOM or hydration.

## Modal

```php
Modal::make()
    ->title('Delete account')
    ->body(Text::make('This cannot be undone.'))
    ->footer(
        Button::make('Cancel')->ghost(),
        Button::make('Delete')->danger(),
    )
    ->open(false)
    ->dismissible();
```

- Root: `data-serixa-modal`, `data-serixa-open`, `hidden` when closed
- Dialog: `role="dialog"`, `aria-modal="true"`, `aria-labelledby` when titled
- Enhanced by **ModalModule** (focus trap, Escape, backdrop)

## Drawer

```php
Drawer::make()
    ->side('right') // or 'left'
    ->title('Filters')
    ->body(Column::make([...])->gap(2))
    ->open(false);
```

- Root: `data-serixa-drawer`
- Panel: `role="dialog"`, `aria-modal="true"`
- Enhanced by **DrawerModule**

## Popover

```php
Popover::make()
    ->trigger(Button::make('More')->ghost())
    ->content(Text::make('Extra details'))
    ->open(false);
```

Anchored panel; enhanced by the `popover` runtime module.

## Tooltip

```php
Tooltip::make()
    ->text('More info')
    ->child(Button::make('?')->ghost());
```

- Trigger: `aria-describedby`
- Tip: `role="tooltip"` (hidden until shown)
- Enhanced by **TooltipModule**

## Dropdown

```php
Dropdown::make()
    ->trigger(Button::make('Account'))
    ->items([
        DropdownItem::make('Profile')->href('/profile'),
        DropdownItem::make('Settings')->href('/settings'),
        DropdownItem::make('Sign out')->danger(),
    ])
    ->open(false);
```

Or compose a menu:

```php
Dropdown::make()
    ->trigger(Button::make('Menu'))
    ->menu(DropdownMenu::make([
        DropdownItem::make('Edit'),
        DropdownItem::make('Delete')->danger(),
    ]));
```

- Trigger: `aria-expanded`, `aria-haspopup="menu"`, `aria-controls`
- Panel: `role="menu"`; items: `role="menuitem"`
- Enhanced by **DropdownModule** (keyboard nav)

## Tabs

```php
Tabs::make()
    ->tab(Tab::make('General')->content(Text::make('General settings'))->active())
    ->tab(Tab::make('Security')->content(Text::make('Security settings')));
```

- List: `role="tablist"`
- Buttons: `role="tab"`, `aria-selected`, `aria-controls`
- Panels: `role="tabpanel"`
- Enhanced by **TabsModule** (arrow keys, roving tabindex)

## Accordion

```php
Accordion::make()
    ->multiple(false)
    ->item(
        AccordionItem::make()
            ->title('What is SERIXA?')
            ->content(Text::make('A PHP-first UI framework.'))
            ->open(true),
    )
    ->item(
        AccordionItem::make()
            ->title('Is JS required?')
            ->content(Text::make('No — enhancement is optional.')),
    );
```

- Root: `data-serixa-accordion`, `data-serixa-multiple`
- Trigger: `aria-expanded`, `aria-controls`
- Enhanced by **AccordionModule**

## Toast

```php
Toast::make()
    ->message('Profile saved')
    ->tone('success') // info | success | warning | danger
    ->open(true);
```

- `role="status"`, `aria-live="polite"`, `aria-atomic="true"`
- Enhanced by **ToastModule**

## Alert

```php
Alert::make()
    ->tone('warning')
    ->title('Caution')
    ->body('Review your settings before continuing.')
    ->dismissible();
```

Static banner: `role="alert"`. Optional dismiss button uses `data-serixa-alert-dismiss` (dismiss module).

## Badge & Chip

```php
Badge::make('Admin')->color('primary'); // neutral | primary | success | warning | danger

Chip::make('PHP')->color('primary')->dismissible();
```

## Spinner, Progress & Skeleton

```php
Spinner::make()->size('md')->label('Loading');

Progress::make()->value(40)->max(100)->label('Upload');
Progress::make()->label('Working…'); // indeterminate (no value)

Skeleton::make()->variant('text')->lines(3);
Skeleton::make()->variant('avatar');
```

| Widget   | ARIA / hooks                                      |
|----------|---------------------------------------------------|
| Spinner  | `role="status"`, `aria-label`                     |
| Progress | `role="progressbar"`, `aria-valuenow` / min / max |
| Skeleton | `aria-hidden` (single) or `role="status"` (group) |

## Registry names

| Name | Class |
|------|-------|
| `modal` | `Modal` |
| `drawer` | `Drawer` |
| `popover` | `Popover` |
| `tooltip` | `Tooltip` |
| `toast` | `Toast` |
| `alert` | `Alert` |
| `badge` | `Badge` |
| `chip` | `Chip` |
| `spinner` | `Spinner` |
| `progress` | `Progress` |
| `skeleton` | `Skeleton` |
| `dropdown` / `dropdownMenu` / `dropdownItem` | Dropdown family |
| `accordion` / `accordionItem` | Accordion family |
| `tabs` / `tab` / `tabPanel` | Tabs family |

## Wiring the runtime

```php
use Serixa\Assets\StyleSheet;
use Serixa\Runtime\ClientRuntime;

$document = Document::make()->title('App')->body($page);
StyleSheet::register($document->assets()); // /assets/serixa.css
ClientRuntime::register($document->assets()); // defer serixa.js
```

Serve `assets/runtime/serixa.js` at the registered public URL (default `/assets/runtime/serixa.js`).

## Out of scope

Client routing, SPA state stores, Virtual DOM, hydration, and business-logic handlers inside modules.
