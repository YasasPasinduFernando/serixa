# SERIXA CRM Starter — Release Report

**Product:** Official CRM Starter (`starters/crm`)  
**Phase:** Product Validation  
**Starter version:** 0.1.0  
**Date:** 2026-07-22  
**Suggested tag:** `serixa-starter-crm-v0.1.0`  
**Push / publish:** No (local only)

## Architecture summary

Application (not a package) mirroring `starters/admin` and `starters/blog`:

- Front controller: `public/index.php` with `?page=` router
- Route map: `routes/Web.php`
- Document assembly: `app/DocumentFactory.php` (StyleSheet + ClientRuntime + ChartRuntime + CalendarRuntime)
- Shell: `layouts/PanelLayout.php` → `AdminLayout`
- Data: `app/Data/Fixtures.php` only (no DB, auth, sessions, ORM)
- Theme: `AdminTheme(DefaultTheme)` + Theme Compiler offline CSS (`composer build`)
- Packages: `serixa/framework`, `admin`, `forms`, `icons`, `charts`, `calendar`

## Pages implemented

| Route | Page |
|-------|------|
| `dashboard` | DashboardPage |
| `leads` | LeadsPage |
| `lead` | LeadDetailsPage |
| `companies` | CompaniesPage |
| `contacts` | ContactsPage |
| `opportunities` | OpportunitiesPage |
| `pipeline` | PipelinePage |
| `tasks` | TasksPage |
| `calendar` | CalendarPage |
| `meetings` | MeetingsPage |
| `invoices` | InvoicesPage |
| `reports` | ReportsPage |
| `analytics` | AnalyticsPage |
| `users` | UsersPage |
| `roles` | RolesPage |
| `settings` | SettingsPage |
| `profile` | ProfilePage |
| `notifications` | NotificationsPage |
| _(unknown)_ | NotFoundPage (HTTP 404) |

## Widgets used

Composed only from existing packages (no starter-local widget forks):

- **Admin:** `Page`, `MetricCard`, `MetricGrid`, `InfoCard`, `Breadcrumb`, `Toolbar` / `ToolbarAction`, `DataTable` helpers (`SearchToolbar`, `TableFilters`, `BulkActions`, `ColumnVisibility`, `RowActions`, `ExportButton`, `ImportButton`), `EmptyState`, `UserChip`, `StatusBadge`, `DescriptionList` / `DescriptionItem`, `AdminLayout` (via PanelLayout)
- **Framework:** `DataTable`, `Select`, `Column`, `Document`
- **Charts:** `LineChart`, `BarChart`, `DonutChart`, `Dataset`, `Series`
- **Calendar:** `MonthView`, `Event`
- **Forms:** `FormBuilder`, `FormBinder`, `Input`, `Select`, `Validator`, `ValidationSummary`, `SuccessMessage`, `OldInput`, `ErrorsBag`

## Framework gaps discovered

See [crm-framework-review.md](crm-framework-review.md). Highlights:

- Chart labels serialization/DX friction (High)
- Icon naming gaps for CRM nav (High)
- No Kanban/pipeline board widget (Medium)
- Multi-package theme mental model (Medium)
- Full-layout HTML payload ~23–70 KB per page (Low/perf)

## Changes made to framework

**None.** Additive package changes were not required to ship the starter. Gaps are documented for later prioritization.

## QA results

| Check | Result |
|-------|--------|
| `composer qa` (CRM starter) | Pass |
| PHPUnit | **24 tests, 138 assertions** — OK |
| PHPStan | No errors |
| PHP-CS-Fixer | Clean (0 files to fix) |
| `php bin/smoke-check.php` | **ALL PAGES RENDERED OK** (18 known + unknown → 404) |

## Smoke results (HTML bytes)

| Page | Bytes |
|------|------:|
| dashboard | 69507 |
| leads | 41687 |
| lead | 24214 |
| companies | 34158 |
| contacts | 35170 |
| opportunities | 36181 |
| pipeline | 41351 |
| tasks | 34498 |
| calendar | 42609 |
| meetings | 30547 |
| invoices | 33189 |
| reports | 33045 |
| analytics | 37294 |
| users | 37677 |
| roles | 30011 |
| settings | 24633 |
| profile | 25973 |
| notifications | 30804 |
| totally-unknown-page | 23189 |

## Performance observations

- Offline CSS compile succeeds; no Tailwind CDN.
- Dashboard is the heaviest page (~70 KB HTML) due to metrics + charts + calendar + activity tables inside full admin chrome.
- Runtime JS (`serixa.js`, `charts.js`, `calendar.js`) is synced into `public/assets/` for offline use.
- Fixtures are request-scoped arrays — no persistence cost; suitable for demos only.

## Documentation

- `starters/crm/README.md`
- `starters/crm/CHANGELOG.md` (0.1.0)
- `docs/starter-crm.md`
- `docs/crm-framework-review.md`

## Commit hash

```text
38b422622e3bdf5c103e83b82d7b70c6412a1d04
```

## Suggested tag

```text
serixa-starter-crm-v0.1.0
```

Local annotated tag points at the starter commit above. Do not push. Do not publish Packagist packages for this starter (application, not a library).
