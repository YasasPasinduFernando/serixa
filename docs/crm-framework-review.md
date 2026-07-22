# CRM Framework Review (Product Validation)

Captured while building `starters/crm` against SERIXA v1.0.0 packages. Framework code was **not** changed to paper over these issues. Priority reflects impact on shipping real CRM apps.

## Critical

None discovered that blocked shipping the starter. All required pages rendered with existing widgets.

## High

### 1. Chart `labels()` do not always appear in rendered config

**DX / API friction.** Dashboard `LineChart` / `BarChart` accept `->labels([...])`, but smoke HTML sometimes shows empty `labels` arrays while series values render. Axis labeling then depends on undocumented fallbacks or client defaults.

**Impact:** Revenue and pipeline charts look incomplete without manual workarounds.

**Recommended additive improvement:** Document the canonical labels path; if labels are dropped during serialization, fix with a non-breaking regression test in `serixa/charts`.

### 2. Icon pack gaps and naming inconsistency

**Missing / awkward icons for CRM navigation:**

| Wanted | What worked | Notes |
|--------|-------------|-------|
| funnel / pipeline | `funnels` | Plural-only name |
| video / meetings | `camera` | No `video-camera` |
| ID / lead detail | `user-circle` | No `identification` |

**Impact:** Sidebar semantics feel approximate; apps invent local aliases.

**Recommended:** Add missing lucide-aligned aliases without removing existing keys; publish an icon catalog page in docs.

### 3. `Page::make(...$widgets)` cannot unpack dynamic arrays mid-list

Building pipeline stage cards required `array_merge` / intermediate arrays because spreading a dynamic list between fixed children is illegal in older PHP call patterns used in pages.

**Impact:** Verbose composition for kanban-style boards.

**Recommended additive API:** `Page::make(...$children)` already works if everything is pre-merged; optional `Page::children(array $widgets)` fluent helper would reduce friction (non-breaking).

## Medium

### 4. Theme stacking for Charts / Calendar

Apps set `AdminTheme(DefaultTheme)`. Charts and Calendar wrap/augment the current theme on demand via their runtimes. The mental model (“who owns CSS tokens?”) is unclear for starters that pull both packages.

**Recommended:** Short “multi-package theme” doc section with the exact `Theme::set` + compile order used by CRM/admin.

### 5. Lead fixture shape vs detail UI

Leads lack a first-class `email` field; Lead Details uses company name in `UserChip` subtitle. Not a framework bug, but the Admin `UserChip` API nudges toward person+email, which CRM leads do not always have.

**Recommended:** Document `UserChip` for non-user entities, or add optional subtitle-only mode examples.

### 6. No first-party Kanban / Pipeline board widget

Pipeline is composed from `InfoCard` + tables + charts. A dedicated drag-and-drop board is absent.

**Priority for CRM product:** Medium (demo works; sales teams will ask).

**Recommended additive:** `PipelineBoard` / `KanbanColumn` in `serixa/admin` later — not required for validation.

### 7. Calendar package surface vs CRM “Meetings”

`MonthView` + `Event` cover the calendar page; Meetings is a separate `DataTable`. Linking a meeting row to a calendar event requires app-level glue (fixtures keys), not a shared identity widget.

**Recommended:** Example recipe in calendar docs: “CRM meetings ↔ calendar events”.

### 8. Router is copy-pasted per starter

`routes/Web.php` + `?page=` is duplicated across blog/admin/crm. Fine for validation; awkward for multi-app product suites.

**Recommended (Low–Medium):** Optional tiny `Serixa\Starter\PageMap` helper — only if multiple official starters keep diverging.

## Low

### 9. Naming inconsistencies across packages

- `SearchToolbar` vs `Toolbar` / `ToolbarAction`
- `EmptyState` under Admin, charts under `Serixa\Charts\Component\*`
- Status via `StatusBadge` vs table cell strings

None blocked CRM; document a “where does this widget live?” index.

### 10. Performance observations

| Page | Approx HTML size (smoke) |
|------|---------------------------|
| Dashboard | ~70 KB |
| Calendar | ~43 KB |
| Lead detail | ~24 KB |
| 404 | ~23 KB |

Full PanelLayout chrome on every page dominates payload. Acceptable for demos; for production CRM, consider fragment/partial rendering or caching compiled CSS (already offline).

No N+1 DB issues (fixtures only). Chart/Calendar JS loaded globally via DocumentFactory — fine for CRM shell, heavier than a marketing page.

### 11. Accessibility

Admin layout provides structure; CRM pages rely on table headers and form labels from Forms. Gaps:

- Metric cards are visual; trend text may need clearer `aria` association
- Calendar month grid depends on package a11y — not re-audited end-to-end in this starter
- Bulk action toolbars are mouse-first

**Recommended:** Extend the v1.0.0 a11y inventory with CRM page spot-checks (dashboard + data table + calendar).

### 12. Missing layouts (not blockers)

No `SplitDetailLayout` (list + preview). Lead Details is a full page. Acceptable; a split layout would help dense CRM UX later.

## Missing widgets (inventory)

| Gap | Workaround used | Priority |
|-----|------------------|----------|
| Kanban / pipeline board | Cards + tables | Medium |
| Activity timeline | `DataTable` / InfoCard list | Medium |
| Quick-action FAB / command palette | `Toolbar` actions | Low |
| Invoice PDF preview | Export button stub | Low |
| Map / territory | None | Low |
| Rich mention `@user` in notes | Plain text | Low |

**Do not duplicate** these inside the starter. Prefer package-level additive widgets when a second real app needs them.

## Recommended additive improvements (summary)

| Priority | Item |
|----------|------|
| High | Fix/document chart labels serialization |
| High | Icon aliases (`funnel`, `video-camera`, `identification`) |
| Medium | Multi-package theme docs |
| Medium | Pipeline/Kanban widget (when productized) |
| Medium | Activity timeline widget |
| Low | Page children helper / starter PageMap |
| Low | CRM a11y spot-check pass |

## Framework changes made during CRM build

**None.** The starter ships using existing public APIs only. Gaps are documented here instead of silently patched in packages.
