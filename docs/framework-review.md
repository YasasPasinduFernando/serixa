# Framework Review (Ecosystem)

Cross-cutting friction observed while growing SERIXA packages and starters under the **v1.0.0 API freeze**.

Do **not** silently modify the framework. Prefer additive APIs only when a real package is blocked.

CRM-specific findings remain in [crm-framework-review.md](crm-framework-review.md).

## Critical

None that blocked Phase 1 enterprise packages (logic-only; minimal framework surface).

## High

| Problem | Impact | Recommendation |
|---------|--------|----------------|
| Chart `labels()` serialization gaps | Charts look unlabeled in CRM demos | Document + regression test in `serixa/charts` |
| Icon naming gaps (`funnel` vs `funnels`, missing video/ID icons) | Weak nav semantics | Additive icon aliases |
| Dynamic `Page::make` children awkward | Verbose pipeline/kanban composition | Optional `Page::children(array)` helper |

## Medium

| Problem | Impact | Recommendation |
|---------|--------|----------------|
| Multi-package theme stacking unclear | Charts/Calendar/Admin theme order | Docs: multi-package theme recipe |
| No first-party Kanban widget | CRM pipeline is cards+tables | Additive admin widget later |
| Duplicated `?page=` routers in starters | Copy-paste across admin/blog/crm | Optional starter PageMap helper |

## Low

| Problem | Impact | Recommendation |
|---------|--------|----------------|
| Full PanelLayout HTML payload | 23–70 KB per page | Partial rendering / caching guidance |
| Naming inconsistency across packages | DX lookup cost | Widget index docs |
| ActivityList is UI-only | Apps need a recorder | Solved by `serixa/activity-log` (Phase 1) — do not overload framework widgets |

## Phase 1 notes

Enterprise packages (`rbac`, `permissions`, `organization`, `teams`, `activity-log`, `audit`) intentionally depend on **framework contracts only** where needed (exceptions namespace). No framework changes were required to ship them.
