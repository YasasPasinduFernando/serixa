# CRM validation debt

Gaps discovered while building `validation/crm` against **public Serixa APIs only** (framework `src/` untouched).

## Missing widgets / features

| Gap | Impact | Severity |
|-----|--------|----------|
| No `Calendar` widget | Calendar page is `EmptyState` placeholder only | High |
| No `Chart` / graph widget | Cannot visualize pipeline or revenue trends | High |
| No date picker / date field | Invoice due dates and calendar filters are plain text | Medium |
| No auth / session helpers | Login is render-only; no protected routes | Medium |
| No router beyond front-controller match | Query-string `?page=` routing is app-level boilerplate | Medium |
| No toast/flash after form POST | Settings/profile/login forms have nowhere to surface success | Low |
| No built-in “active nav” for Sidebar | App must track `$active` and call `Link::active()` manually | Low |
| CLI leftover pages (`HomePage`, `ContactsPage`) | Generated stubs unused by CRM routes | Low |
| `AppLayout` vs `CrmLayout` | CLI ships `AppLayout`; app uses `CrmLayout` — dual chrome | Low |

## API friction

- Layout chrome (`Scaffold` + `AppBar` + `Sidebar` + assets + `ClientRuntime`) is repetitive; no first-class “app shell” helper for apps.
- `CustomerCard` must re-compose `Card`/`Avatar`/`Text`/`Badge`/`Link` manually — fine, but no documented app-component recipe beyond stubs.
- Forms are SSR-only; no validation or submission pipeline in public API (by design, but noted for DX).
- `Modal` open requires `data-serixa-open-modal` + runtime script path served by the host app.

## Intentional non-fixes

Per validation rules: do **not** add Calendar/Chart widgets or redesign APIs based on these findings. Record only.
