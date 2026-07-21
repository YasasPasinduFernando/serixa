# SERIXA API Review — Demo DX Findings

**Scope:** Through Data Display (`v0.7.0`)  
**Updated:** 2026-07-22  

---

## Resolved by v0.6.0 (UI Foundation)

Forms, Link, Scaffold chrome, FieldGroup/HelperText/ErrorText, fluent Padding, `Serixa\` namespace.

## Resolved by v0.7.0 (Data Display)

| Former placeholder / issue | Resolution |
|----------------------------|------------|
| DataTable / Table | `DataTable` + table primitives |
| SearchField | `SearchField` (render-only) |
| Pagination | `Pagination` + `urlForPage()` |
| Toolbar / PageHeader | `Toolbar`, `PageHeader` |
| StatCard / Metric | `StatCard`, `Metric` |
| ActivityList | `ActivityList` / `ActivityItem` |
| EmptyState | `EmptyState` |
| Avatar | `Avatar` |
| Logo / Image | `Logo`, `Image` |

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

Playground CRUD and Dashboard are demo-complete for **SSR data display**. Remaining gaps are charts, badges, and interactive overlays — not blocking for static admin shells.
