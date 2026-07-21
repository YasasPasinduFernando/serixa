# SERIXA Framework Validation Report

**Version under test:** v0.10.0  
**Milestone:** Pre-1.0 real-world validation  
**Date:** 2026-07-21  
**Rules followed:** Public APIs only · No new framework features/widgets · No runtime redesign · Fix only blockers · Document everything else  

Related artifacts:

- Apps: `validation/blog`, `validation/admin-dashboard`, `validation/crm`
- [docs/performance.md](../docs/performance.md)
- [docs/dx-audit.md](../docs/dx-audit.md)
- App debt notes: `validation/admin-dashboard/DEBT.md`, `validation/crm/DEBT.md`

---

## Executive verdict

SERIXA **can build real multi-page SSR applications** (blog, admin shell, CRM) using only public APIs and the CLI. Render quality, widget coverage for common admin/blog UI, and framework QA (`composer qa`) are solid.

It is **not yet frictionless as an application framework**: no router, no auth, missing Chart/Calendar, verbose layout/PE wiring, and monorepo path-repo recursion harms DX tooling. Treat v0.10.0 as a **feature-complete UI engine**; **v1.0 readiness is conditional** (see end).

---

## Applications created

| Project | CLI | Pages / surfaces | Smoke |
|---------|-----|------------------|-------|
| `validation/blog` | `serixa new blog` | Home, Articles, Article, Categories, About, Contact, Login, Profile, 404 | 9/0 OK |
| `validation/admin-dashboard` | `serixa new admin-dashboard --type=blog` | Dashboard, Analytics, Users, Roles, CRUD, Settings, Profile, Notifications, Login (+ stubs) | 9/0 OK |
| `validation/crm` | `serixa new crm` | Dashboard, Customers, Companies, Invoices, Tasks, Calendar (placeholder), Profile, Settings, Login | 9/0 OK |

All three were **CLI-generated**, then grown with `make:page` / `make:component` / `make:layout`. Framework `src/` was **not** modified for feature work.

### Feature coverage vs mission

| Requirement | Blog | Admin | CRM |
|-------------|:----:|:-----:|:---:|
| Layouts / nav / scaffold | ✓ | ✓ | ✓ |
| Forms / buttons / cards | ✓ | ✓ | ✓ |
| Search / pagination UI | ✓ | ✓ | ✓ |
| Interactive widgets | ✓ | ✓ Modal/Dropdown/Accordion/Tabs/Toast | ✓ |
| Charts | — | Placeholder (`Skeleton`) | Missing |
| Calendar | — | — | Placeholder (`EmptyState`) |

---

## LOC statistics (app source only)

Vendor excluded (required: path junctions recurse if scanned).

| App | Files | LOC |
|-----|------:|----:|
| blog | 16 | 623 |
| admin-dashboard | 18 | 1 012 |
| crm | 17 | 982 |
| **Total** | **51** | **2 617** |

| Nesting / page size | blog | admin | crm |
|---------------------|-----:|------:|----:|
| Avg page LOC | 43.6 | 64.5 | 64.0 |
| Avg max indent | 4.2 | 6.3 | 6.0 |
| Avg method-chain (`->`) / page | 19.6 | 29.0 | 27.9 |

---

## Performance metrics

| Metric | Value |
|--------|------:|
| Runtime `serixa.js` | 20 460 bytes |
| HTML range (sample pages) | 4 593–12 204 bytes |
| Render time range | 14–43 ms |
| Peak memory (page render) | ~4 MiB |
| Theme: 3 000 class lookups | ~2 287 ms (~0.8 ms/lookup) |
| CLI new / build / docs | ~0.2 s each |
| `composer install` (path app) | ~6.4 s |
| PHPUnit | 119 tests · ~1.2 s · OK |
| PHPStan / CS Fixer | OK / 0 fixes |

Full tables: [docs/performance.md](../docs/performance.md).

---

## Scores

| Dimension | Score | Notes |
|-----------|------:|-------|
| **Developer experience** | **6 / 10** | Fluent widgets + CLI strong; onboarding, routing, PE wiring, path-repo weak |
| **Documentation** | **6 / 10** | Accurate references; no beginner tutorial path |
| **Accessibility** | **7 / 10** | Widgets emit ARIA/roles; `AccessibilityPass` is a no-op placeholder; no automated a11y CI |
| **Performance (SSR)** | **7 / 10** | Small HTML/runtime; theme lookup cost is a concern at scale |
| **API completeness (UI)** | **8 / 10** | Broad widget set for dashboards/blogs; Chart/Calendar gaps |

---

## Framework strengths

1. **Fluent PHP UI** — Flutter-like composition works for real screens.
2. **SSR-first** — Predictable HTML; tiny PE runtime (~20 KiB).
3. **Layout & chrome** — `Scaffold`, `AppBar`, `Sidebar`, `PageHeader` suffice for admin/CRM shells.
4. **Data display** — Tables, stats, pagination, activity lists usable without extension.
5. **Interactive set** — Modal, Drawer, Dropdown, Tabs, Accordion, Toast cover common admin UX.
6. **Document/assets** — Full pages with SEO/asset hooks are achievable.
7. **CLI scaffolding** — `new` + `make:*` produce runnable apps in seconds.
8. **Stewardship** — Tests, PHPStan, CS Fixer green; API stability docs exist.
9. **Theme tokens** — Consistent Tailwind class mapping across widgets.
10. **Escaping defaults** — Safer SSR output posture.

---

## Framework weaknesses

1. **No first-class router** — Every app copies `match ($_GET['page'])`.
2. **No auth/session helpers** — Login is cosmetic.
3. **No Chart / Calendar widgets** — CRM/admin analytics incomplete.
4. **PE wiring is attribute-oriented** — Verbose, easy to mistype.
5. **Layout boilerplate** — Assets + `ClientRuntime` + shell repeated.
6. **Import / nesting verbosity** — Large pages are deep trees.
7. **Monorepo path-repo recursion** — Critical tooling hazard.
8. **Docs are reference-not-tutorial** — Slow first app.
9. **CLI presets incomplete** — No admin preset; leftover stubs.
10. **AccessibilityPass unused** — Pipeline promise vs reality.
11. **Theme resolution cost** — ~0.8 ms per `classes()` call in microbench.
12. **CDN Tailwind in demos** — Production story unclear.
13. **Forms are render-only** — No validation/submit pipeline.
14. **Active nav is manual** — Apps track `$active` themselves.

---

## Accessibility

| Finding | Severity |
|---------|----------|
| Many interactive widgets emit `aria-*` / `role` (admin dashboard sample: 17 aria attrs, 14 roles) | Positive |
| Forms can associate labels (`for`/`id` present on contact) | Positive |
| `AccessibilityPass` only sets pipeline meta — no tree audit | Medium |
| No axe/pa11y (or equivalent) in CI | Medium |
| Modal/Toast focus management depends on runtime; not fully audited here | Medium |
| Color/contrast depends on Tailwind theme choices in apps | Low |

**Accessibility score: 7 / 10**

---

## Boilerplate analysis

Repeated in all three apps:

- Front-controller routing
- Layout `wrap()` registering JS/CSS + `ClientRuntime`
- `PageHeader` + `Column` + `SizedBox` page rhythm
- Manual active nav highlighting
- Static fixture arrays for tables/lists
- PE `data-serixa-*` attributes for toasts/modals

Estimated **boilerplate share:** ~25–35% of app LOC (routing + layout wrap + nav state).

---

## Prioritized issues

### Critical

| ID | Issue |
|----|-------|
| C1 | Path-repository junction recursion when apps live under the framework tree (`vendor/serixa/framework` → nested `validation/*/vendor`) — breaks recursive tooling |
| C2 | No documented safe install path for in-tree validation/playground apps |

### High

| ID | Issue |
|----|-------|
| H1 | Missing Chart widget for analytics |
| H2 | Missing Calendar widget (CRM calendar is placeholder) |
| H3 | No beginner end-to-end tutorial |
| H4 | Theme `classes()` lookup cost (~0.8 ms each) |
| H5 | PE Toast/Modal binding is manual attributes only |
| H6 | No router abstraction (or official “BYO router” recipe) |

### Medium

| ID | Issue |
|----|-------|
| M1 | No admin CLI preset |
| M2 | Leftover generated stubs (`PostsPage`, `ContactsPage`, unused `AppLayout`) |
| M3 | No date picker / date field |
| M4 | Forms lack validation/error pipeline helpers |
| M5 | AccessibilityPass is a stub |
| M6 | Dual Widget/Component vocabulary |
| M7 | Production CSS strategy (CDN Tailwind) undocumented |
| M8 | Active sidebar/nav helpers missing |
| M9 | DataTable cell composition helpers limited |

### Low

| ID | Issue |
|----|-------|
| L1 | StatCard vs Metric overlap |
| L2 | Long import lists / no facade helpers |
| L3 | Flash/toast after POST not prescribed |
| L4 | `serixa docs` output not linked from README getting started |
| L5 | Calendar “placeholder” should be explicit EmptyState recipe in docs |

---

## Top 20 API improvements

*(Record only — do not implement in this milestone.)*

1. Official **app shell** helper: register runtime assets + optional Scaffold defaults.
2. Fluent **Toast::bind(Button)** / **Modal::openFrom(Button)** instead of raw attributes.
3. Lightweight **page router** interface or documented adapter for popular routers.
4. **NavItem** / sidebar item with `active` / icon slots.
5. **Chart** widget (SSR-friendly placeholder + progressive enhance).
6. **Calendar** month grid widget.
7. **DateField** / date input component.
8. Memoized **Theme::classes** API or internal cache (public guarantee of speed).
9. **Form::validate** / error bag display helpers (even client-agnostic).
10. **DataTable::column()** typed cell builders (badge, actions, avatar).
11. Reduce **Widget vs Component** surface in public docs/API aliases.
12. **Flash** / session-agnostic notice slot on Document/Scaffold.
13. **Breadcrumb** widget.
14. Shorthand **page()** factory returning Document + body composition.
15. **SearchField** composite (Input + Button + form GET).
16. Pagination linked to query-string helpers.
17. Consistent naming: `ghost`/`secondary`/`danger` across all actionable widgets.
18. Deduplicate KPI APIs (`StatCard` vs `Metric`) or document when to use which.
19. Layout spacing helpers to cut `SizedBox` noise (`Column::gap()` already — push adoption).
20. Public **AccessibilityAuditor** (or flesh out AccessibilityPass) instead of silent meta flag.

---

## Top 20 documentation improvements

1. **Tutorial: Build a blog** (CLI → pages → layout → deploy).
2. **Getting Started** hub page (single entry).
3. App shell recipe (Document + assets + ClientRuntime).
4. Routing cookbook (query string, path info, external router).
5. Interactive PE cookbook with copy-paste Toast/Modal/Tabs.
6. Admin dashboard walkthrough.
7. CRM walkthrough + honest Calendar/Chart gaps.
8. Auth “out of scope” + integration notes.
9. Production assets (compile Tailwind, pin runtime path).
10. Theme mental model for beginners (class maps ≠ CSS file).
11. Explain CLI presets and leftover stubs.
12. Monorepo / path-repo warnings.
13. Widget gallery index (A–Z with examples).
14. Accessibility guide (what widgets guarantee).
15. Link `serixa docs` output from README.
16. Form patterns (FieldGroup, errors, helper text).
17. DataTable advanced cells example.
18. Sidebar active state example.
19. Troubleshooting: runtime JS 404, PE no-ops.
20. “Public API surface” cheat sheet for v1.0.

---

## Top 20 performance opportunities

1. Cache `DefaultTheme::classes($component, $state)` by key.
2. Precompute common variant maps at theme construct.
3. Warm-render benchmarks in CI (exclude autoload).
4. Avoid path-repo recursion (mirror install for nested apps).
5. Tree-level class resolution batching in pipeline.
6. Reduce redundant wrapper widgets in generated HTML.
7. Optional Tailwind purge / non-CDN CSS for demos.
8. Lazy-register PE runtime modules only when widgets present.
9. Profile HtmlWriter string concatenation vs buffers.
10. Share Renderer instance across requests in long-running SAPIs.
11. Document memory_get_usage slab artifacts for app authors.
12. Shrink duplicate SVG/icon markup if introduced later.
13. Pagination/list pages: fragment caching hooks (app-level).
14. CLI PHAR or opcache-friendly bootstrap to cut ~170 ms version.
15. Parallelize `serixa build` steps if they grow.
16. Avoid re-resolving tokens for identical sibling widgets.
17. Measure full Document SEO head cost separately.
18. Compress `serixa.js` (minify already small; ensure production minify).
19. HTTP caching headers recipe for static runtime assets.
20. Stress test 500+ widget trees for theme lookup cliffs.

---

## Quality gates

| Gate | Result |
|------|--------|
| `composer test` / PHPUnit | **PASS** (119 tests) |
| PHPStan | **PASS** |
| PHP-CS-Fixer | **PASS** (0 fixes) |
| Validation apps render | **PASS** (smoke 9/0 each) |
| Framework `src/` feature changes for apps | **None** (as required) |

---

## Future improvements (post-validation backlog)

Prioritize Critical → High before calling v1.0:

1. Fix monorepo install story (C1/C2).
2. Ship beginner tutorial + Getting Started (H3).
3. Theme class memoization (H4).
4. Decide Chart/Calendar for v1.0 scope or explicitly defer with EmptyState recipes (H1/H2).
5. PE fluent binders (H5).
6. Router stance: implement minimal or document BYO (H6).
7. CLI admin preset + stub cleanup (M1/M2).
8. AccessibilityPass real work or remove from mental model (M5).

---

## Overall framework readiness for v1.0

| Question | Answer |
|----------|--------|
| Feature-complete as **PHP UI / SSR widget engine**? | **Yes** (pre-1.0 claim holds) |
| Ready as **batteries-included app framework**? | **No** (router, auth, charts/calendar, tutorials) |
| Can teams build production UIs with public APIs today? | **Yes**, with app-owned routing/auth and accepted gaps |
| Blockers to a responsible **v1.0** tag | C1/C2 tooling, H3 docs onboarding, H4 theme perf, explicit scope for H1/H2/H6 |

**Verdict:** **Conditionally ready for v1.0 as a UI framework** after Critical install/tooling clarity, beginner documentation, and either shipping or formally deferring Chart/Calendar/router. **Not ready** to market as a full-stack application framework without those decisions.

---

## Stop line

Validation complete. **No improvement implementation** was started per mission rules. Next work should triage the prioritized backlog deliberately — not ad-hoc feature adds.
