# SERIXA API Review — Demo DX Findings

**Scope:** Through Progressive Enhancement (`v0.8.0`)  
**Updated:** 2026-07-22

---

## Resolved by v0.6.0 (UI Foundation)

Forms, Link, Scaffold chrome, FieldGroup/HelperText/ErrorText, fluent Padding, `Serixa\` namespace.

## Resolved by v0.7.0 (Data Display)

DataTable dual API, SearchField, Pagination, Toolbar, PageHeader, StatCard, Metric, ActivityList, EmptyState, Avatar, Logo, Image.

## Resolved by v0.8.0 (Progressive Enhancement)

| Former gap / request | Resolution |
|----------------------|------------|
| Fluent DOM events | `onClick` / `onInput` / `onSubmit` / … → `data-serixa-on-*` |
| HTML constraints | `min`/`max`/`minLength`/`maxLength`/`pattern`/`readonly` (+ existing `required`) |
| Client runtime | `assets/runtime/serixa.js` via `ClientRuntime::register()` |
| Event metadata catalog | `EventRegistry` (no server execution) |
| Login demo | `onSubmit('login')`, `required`, length constraints, submit `onClick` |
| CRUD search / actions | `SearchField::onInput('search')`, Create `onClick('createUser')` |

No JS business logic in the playground — metadata and native HTML only.

---

## Remaining future work

| Item | Notes | Target |
|------|-------|--------|
| `Chart` | Still placeholder on Dashboard | later |
| `Badge` | Use `Text` for now | later |
| `LinkField` / social icon row | Profile uses Input + Link | later |
| Button-as-link | Logout still a `Button` | M8.1+ |
| Validation adapters / engine | Constraints are HTML-only | later |
| Modal / Tabs / focus trap | Optional PE modules | M8.1 |
| Client search/sort | Intentionally out of scope | — |

---

## Verdict

Playground demonstrates **SSR + optional PE**. Pages remain usable without JavaScript; the runtime only discovers event metadata.
