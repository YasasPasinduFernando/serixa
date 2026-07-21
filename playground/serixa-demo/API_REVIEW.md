# SERIXA API Review — Demo DX Findings

**Scope:** Framework through UI Foundation (`v0.6.0`)  
**Method:** Consume `serixa/framework` via path repo; build five demo pages  
**Updated:** 2026-07-22  

---

## Resolved by Milestone 6.0 (UI Foundation)

| Former issue | Resolution |
|--------------|------------|
| No `Form` surface | `Form::make()->method()->action()->child(...)` |
| No `Input` / `PasswordInput` / `Textarea` / `Select` | Implemented |
| No `Checkbox` / Switch UI | `Checkbox`, `SwitchControl` (`Switch` is a PHP reserved word) |
| No `Label` | `Label::make()->for($id)` |
| No `Link` | `Link::make()->href()->active()` |
| No app chrome widgets | `Scaffold` + `AppBar` + `Sidebar` + `MainContent` + `Footer` |
| No `FieldGroup` / helper / error text | `FieldGroup`, `HelperText`, `ErrorText` |
| Verbose static-only Padding | Fluent `Padding::make()->x()->y()->child(...)` (M5.2) |
| Namespace mismatch | Canonical `Serixa\` (M5.2) |

Login and Settings pages now use real form + chrome widgets. Shell uses Scaffold composition.

---

## What still works well

1. Fluent widget construction remains consistent across foundation widgets.
2. Document engine + Tailwind CDN is enough for local demos.
3. Theme-emitted classes make forms and chrome readable without custom CSS.
4. Escape-by-default still prevents unsafe DIY HTML.

---

## Remaining awkward / open DX

| Issue | Detail | Severity | Target |
|-------|--------|----------|--------|
| No button-as-link | Logout/nav CTAs that should be `<a class="btn">` still need `Link` or non-navigating `Button` | Medium | M7+ |
| Stat / metric repetition | Dashboard KPI cards are still `Card` + `Text` | Medium | M6.1+ |
| Tailwind CDN is DIY | No `Document::tailwindPlayCdn()` helper | Low | later |
| Switch class name | Must use `SwitchControl` because PHP reserves `switch` | Low | documented |
| Validation display | `invalid()` / `ErrorText` exist; no validation engine or adapters | Medium | M6.1 |

---

## Remaining placeholders (future milestones)

### Still missing on demo pages

| Widget | Pages | Suggested milestone |
|--------|-------|---------------------|
| `Logo` / `Brand` / `Image` | Login | later |
| `Avatar` | Profile | M6.1 / M7 |
| `LinkField` / social icon row | Profile | later |
| `SearchField` | CRUD | M6.1 |
| `DataTable` / `Table` | CRUD | M6.1 |
| `Pagination` | CRUD | M6.1 |
| `StatCard` / `Metric` | Dashboard | M6.1 |
| `Chart` | Dashboard | later |
| `ActivityList` / `List` / `ListTile` | Dashboard | M6.1 / M7 |
| `Modal`, `Dropdown`, `Toast`, `Badge`, `Icon` | polish | M7 |

### Still useful helpers

- `PageHeader` (title + actions)
- `Toolbar` (search + filters + CTA)
- Optional demo `tailwindPlayCdn()` on Document

---

## Verdict

**M6.0 UI Foundation unblocks real login/settings composition.** Chrome + forms + links are production-shaped for render-only apps. Remaining gaps are **data display** (tables, stats, lists), **media** (avatar/logo), and **interactive overlays** (M7) — plus optional validation display adapters (M6.1).
