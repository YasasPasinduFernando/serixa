# SERIXA API Review — Demo DX Findings

**Scope:** Through Data Display (`v0.7.0`)  
**Updated:** 2026-07-22 (release audit)

---

## Resolved by v0.6.0 (UI Foundation)

Forms, Link, Scaffold chrome, FieldGroup/HelperText/ErrorText, fluent Padding, `Serixa\` namespace.

## Resolved by v0.7.0 (Data Display)

| Former placeholder / issue | Resolution |
|----------------------------|------------|
| DataTable / Table | `DataTable` + table primitives |
| DataTable dual API | `headers()`/`rows()` **and** `child(Table)` + `Table::head()`/`body()` |
| SearchField | `SearchField` (render-only) |
| Pagination | `Pagination` + `urlForPage()` |
| Toolbar / PageHeader | `Toolbar`, `PageHeader` |
| StatCard / Metric | `StatCard`, `Metric` (both on Dashboard) |
| ActivityList | `ActivityList` / `ActivityItem` |
| EmptyState | `EmptyState` |
| Avatar | `Avatar` on Profile |
| Logo / Image | `Logo` + `Image` on Profile; `Logo` on Login |

Dashboard, CRUD, Profile, and Login placeholders for the above are removed in the playground.

---

## Remaining future work

| Item | Notes | Target |
|------|-------|--------|
| `Chart` | Still placeholder on Dashboard | later |
| `Badge` | Mentioned in early DataTable sketches; use `Text` for now | later |
| `LinkField` / social icon row | Profile uses Input + Link | later |
| Button-as-link | Logout still a `Button` | M7+ |
| Validation adapters | `invalid()` / `ErrorText` exist; no engine | M6.1-style follow-up |
| Client search/sort | Intentionally out of scope | — |

---

## Verdict

Playground CRUD and Dashboard are demo-complete for **SSR data display**. DataTable supports both convenience and composition APIs. Remaining gaps are charts, badges, and interactive overlays — not blocking for static admin shells.
