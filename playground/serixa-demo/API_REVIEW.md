# SERIXA API Review — Demo DX Findings

**Scope:** Milestones 1–5 public API only  
**Method:** Build five realistic pages in `playground/serixa-demo` without changing the framework  
**Date:** 2026-07-22  

---

## What worked well

1. **Fluent widget construction** — `Button::make('Save')->primary()->large()` and `Text::make(...)->as('h1')->size('xl')->weight('bold')` feel predictable and Flutter-like.
2. **Layout primitives are enough for chrome** — `Row`, `Column`, `Expanded`, `Spacer`, `Padding`, `Container`, `Center`, `Card`, `Divider` can approximate a sidebar + top bar + content shell.
3. **Document engine is the right page entrypoint** — `Document::make()->title(...)->body(...)->assets()->css/js(...)` maps cleanly to a full HTML page without a framework “app kernel”.
4. **Theme class emission** — Buttons/cards/text get sensible Tailwind classes out of the box; pairing with the Tailwind CDN made the demo readable quickly.
5. **Composer package identity** — Path-requiring `serixa/framework` works as a real consumer would expect.
6. **Escape-by-default** — String children are escaped; that is correct for safety (even when it blocked inventing fake HTML inputs).
7. **Variant aliases** — `primary()`, `ghost()`, `danger()`, `small()` reduce noise versus only `variant()` / `size()`.

---

## What felt awkward

| Issue | Detail | Severity |
|-------|--------|----------|
| Product vs namespace | Package is SERIXA; every `use` is `Serixa\...`. Cognitive tax for new contributors. | High |
| No navigation primitive | Had to use `Text::make(...)->as('a')->attribute('href', ...)` for every link. Easy to forget `as('a')`. | High |
| Button is never a link | Login/nav CTAs that should be `<a class="btn">` must be Text or a non-submitting Button. | High |
| No Form surface | Cannot wrap login/settings in `<form method="post">` via widgets; strings are escaped so raw HTML is not an escape hatch. | High |
| Padding API verbosity | `Padding::y(4, Padding::x(6, $child))` for “px-6 py-4”. No `symmetric()` / instance chaining after `make()`. | Medium |
| Card children style | Mix of `Card::make($a, $b)` and `->child()` / `->title()`; pages prefer varargs, docs show `child()` — both work, style drifts. | Low |
| Stat / metric repetition | Dashboard KPI cards are copy-pasted `Card` + two `Text` nodes. | Medium |
| Shell composition depth | Authenticated layout nests Column → Row → Expanded → Padding → page Column. Readable but tall. | Medium |
| Tailwind CDN is DIY | Document assets accept a JS URL, but there is no documented “enable utilities for local demo” helper. | Low |
| Active nav styling | Manual `if ($page === $active)` on each link; no `NavItem::active()`. | Medium |
| Logout Button vs route | `Button::make('Logout')` cannot navigate; needs Link or form post. | Medium |
| Missing widget visibility | Without a demo helper, missing controls would silently become empty space or invent HTML. Placeholders were intentional. | — |

---

## Missing widgets (recorded from pages)

### Forms (blocking for real apps)

| Widget | Needed on |
|--------|-----------|
| `Input` / `TextField` | Login, Settings, Profile, CRUD search |
| `PasswordInput` | Login |
| `Textarea` | Profile bio |
| `Checkbox` | Remember me, notification options |
| `Switch` / `Toggle` | Theme switch, push notifications |
| `Select` | Settings timezone, CRUD filters |
| `Form` | Login / Settings submit grouping |
| `Label` | Associated field labels |
| `SearchField` | CRUD toolbar |

### Data display

| Widget | Needed on |
|--------|-----------|
| `DataTable` / `Table` | CRUD |
| `Pagination` | CRUD |
| `StatCard` / `Metric` | Dashboard |
| `Chart` (or ChartPlaceholder contract) | Dashboard |
| `ActivityList` / `List` / `ListTile` | Dashboard recent activity |

### Chrome / navigation

| Widget | Needed on |
|--------|-----------|
| `Sidebar` | App shell |
| `AppBar` / `TopNav` | App shell |
| `NavItem` / `Link` | All pages |
| `Footer` | App shell |
| `Logo` / `Brand` | Login |
| `Avatar` | Profile |
| `LinkField` / social link row | Profile |

### Actions / overlays (not used as hard requirements here, but blocked polish)

| Widget | Notes |
|--------|-------|
| `Modal` | Edit profile / confirm delete |
| `Dropdown` | Row actions on CRUD |
| `Icon` / `IconButton` | Compact toolbar actions |
| `Badge` | Notification counts |
| `Image` | Avatar fallback / empty states |

---

## Missing layouts / helpers

- **`AppShell` / `Scaffold`** — sidebar + app bar + body + footer as one composition (Flutter `Scaffold` analogue).
- **`FormLayout` / `FieldGroup`** — label + control + hint/error stack.
- **`PageHeader`** — title + subtitle + primary actions row (repeated on every page).
- **`Toolbar`** — search + filters + primary CTA (CRUD).
- **`Link` helper** — `Link::make('Dashboard', '/?page=dashboard')->active($bool)`.
- **Document helper** — optional `Document::make()->tailwindPlayCdn()` (or similar) for demos only.

---

## Suggestions for Milestone 6 (do not implement here)

Prioritize **forms that unblock the login/settings/profile loop**:

1. **`Input`, `Textarea`, `Select`, `Checkbox`, `Label`** with fluent `name()`, `value()`, `placeholder()`, `disabled()`.
2. **`Form`** widget that renders `<form>` and accepts widget children (critical — escape rules block DIY).
3. **`Switch`** for theme + notification toggles (align with existing `WidgetContext` colorMode story).
4. **`Link`** (and optionally `Button::asLink()` / `href()`) for navigation CTAs.
5. **Field validation display (UI only)** — error text / invalid tone, even without a validator engine.

Nice-to-have immediately after (still M6-adjacent or early M7):

6. `SearchField`, `DataTable`, `Pagination` for CRUD demos.  
7. `Avatar`, `StatCard`, lightweight `List`/`ListTile`.  
8. `Scaffold`/`AppShell` to cut shell boilerplate.

**Out of scope reminder:** Do not add routing, reactivity, or invent framework widgets from this playground.

---

## Consumer notes (non-widget)

- Path repository `../../` + `"serixa/framework": "*"` worked.
- Runtime namespace remains `Serixa\` — document this loudly in the framework README for adopters (already mentioned; still the #1 onboarding friction in this exercise).
- `Renderer::render($document)` is a clean single call for pages.

---

## Verdict

SERIXA’s **layout + document + button/text/card** core is usable and pleasant for static composition. It is **not yet sufficient** for a believable admin/login UX: forms, links, and table primitives are the gap. Milestone 6 should focus on forms + link/navigation before richer interactive widgets.
