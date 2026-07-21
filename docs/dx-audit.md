# SERIXA Developer Experience Audit (v0.10.0)

Auditor persona: brand-new PHP developer building a simple blog with public docs + CLI only.  
Date: 2026-07-21 · Framework: **v0.10.0** (feature-complete pre-1.0).

Companion metrics: [performance.md](performance.md) · Full findings: [`validation/REPORT.md`](../validation/REPORT.md).

---

## Beginner blog attempt (simulated)

### Goal

Ship a small blog: home, article list, article detail, about, contact, login — using **only** documented public APIs.

### Path taken

1. Read root `README.md` → Quick start shows fragment rendering (`Renderer` + widgets), not a full app.
2. Discover CLI via `docs/cli.md` / `docs/project-generator.md`.
3. Run `php bin/serixa new blog` → generates project.
4. `composer install` → works with path repository when run from monorepo.
5. `php -S localhost:8000 -t public` → home stub works.
6. Use `serixa make:page` / `make:component` / `make:layout` to grow the app.
7. Compose pages with widgets from subsystem docs (`ui-foundation`, `data-display`, `interactive-components`, `layout-engine`, `document-engine`).

### Time estimate (experienced auditor with prior framework knowledge ≈ lower bound)

| Step | Est. time |
|------|-----------:|
| Find CLI / generate project | 10–15 min |
| Understand Document + assets + ClientRuntime | 30–45 min |
| Layout shell (Scaffold / AppBar / Footer) | 20–30 min |
| Article list + Card + Pagination + Search UI | 45–60 min |
| Forms (contact / login) | 30–40 min |
| Wire front-controller `match` routing | 20–30 min |
| Fix runtime JS path / PE attributes for Toast/Modal | 20–40 min |
| **Total to a credible demo blog** | **~3–5 hours** |

True beginner without knowing which docs to open: **1–2 days** (docs are subsystem-oriented, not tutorial-oriented).

### Setup time (machine)

| Step | Measured |
|------|----------|
| `serixa new blog` | ~0.2 s |
| `composer install` (path repo) | ~6.4 s |
| First HTTP render | interactive (PHP built-in server) |

Scaffolding quality is **good**; documentation onboarding is the bottleneck.

---

## Documentation clarity

### Strengths

- Clear subsystem docs for rendering, layout, foundations, document engine, PE, interactive widgets, CLI.
- ADRs explain stability and namespace decisions.
- CLI command tables are accurate (`version`, `new`, `make:*`, `build`, `docs`, `dev`).
- No broken relative Markdown links found under `docs/` (spot check, 2026-07-21).

### Gaps (beginner-critical)

| Gap | Impact |
|-----|--------|
| No end-to-end **“Build a blog”** tutorial | Highest friction |
| README quick start is fragment-only | Misleading first impression |
| No “app shell” recipe (Document + assets + ClientRuntime + Scaffold) | Every app reinvents layout wrap |
| PE / interactive docs assume attribute knowledge | Toast/Modal wiring is trial-and-error |
| No routing guide | Apps invent `?page=` match routers |
| No auth/session story (even “out of scope”) | Login pages look unfinished |
| Preset docs say `blog` / `crm` / `ecommerce` only | No `admin` preset; `admin-dashboard` needed `--type=blog` |
| Leftover stub pages (`PostsPage`, `HomePage`) unexplained | Noise in generated trees |

### Confusing sections

- **Widget vs Component** naming (ADR helps; day-1 still confuses).
- **Theme** described as tokens + Tailwind classes; beginners expect CSS files.
- **AccessibilityPass** is a pipeline placeholder — docs do not say “widgets emit ARIA; pass is no-op enrichment.”
- Multiple overlapping entry docs (`scaffolding`, `project-generator`, `templates`, `cli`) without a single “start here” index.

### Missing examples

- Full page `Document` with SEO + assets.
- Sidebar + active link pattern.
- DataTable with Badge/Button cells.
- Form + FieldGroup + validation display (even static errors).
- Serving `/assets/runtime/serixa.js` from `public/`.

---

## CLI usability

| Criterion | Score intuition | Notes |
|-----------|-----------------|-------|
| Discoverability | Good | `list` / `help` / banner |
| `new` presets | Fair | blog/crm/ecommerce only; no admin |
| `make:*` | Good | Fast stubs, no overwrite |
| Path repo inside monorepo | **Poor** | Recursive vendor junctions; disk tools hang |
| Generated README | Fair | Minimal next steps |
| Leftover stubs | Fair | Unused `PostsPage` / `ContactsPage` / `AppLayout` |

---

## API consistency & verbosity (from building three apps)

### Consistent

- `::make()` entry points.
- Fluent modifiers (`->primary()`, `->title()`, layout `Row`/`Column`).
- Theme-backed class maps.

### Awkward / verbose

- Long import lists per page (10–20 `use` statements common).
- Nested `Column`/`Row`/`SizedBox` trees (admin pages max indent ~8–10).
- Manual PE attributes (`data-serixa-show-toast`, modal open) instead of fluent binders.
- Layout wrap repeated: assets + `ClientRuntime::register` + Scaffold chrome.
- No helper for “page with title + subtitle + actions” beyond composing `PageHeader` yourself (widget exists; recipe missing).

### Duplicate / overlapping concepts

- `StatCard` vs `Metric` vs `Card` + `Text` for KPI tiles.
- `Widget` / `Component` dual vocabulary.
- CLI `AppLayout` vs app-specific layouts left side-by-side.

---

## Boilerplate & LOC (app source only; vendor excluded)

| App | PHP files (app dirs) | LOC |
|-----|---------------------:|----:|
| blog | 16 | 623 |
| admin-dashboard | 18 | 1 012 |
| crm | 17 | 982 |
| **Total** | **51** | **2 617** |

| Page stats | blog | admin | crm |
|------------|-----:|------:|----:|
| Avg page LOC | 43.6 | 64.5 | 64.0 |
| Avg max indent | 4.2 | 6.3 | 6.0 |
| Avg `->` calls / page | 19.6 | 29.0 | 27.9 |

Rough **setup time** for a new page after patterns exist: **5–15 minutes** (make:page + copy layout shell + compose widgets).

---

## Beginner friendliness scorecard

| Area | /10 | Rationale |
|------|----:|-----------|
| First hour success | 5 | Fragment hello works; full app unclear |
| Scaffolding | 8 | CLI generates runnable trees quickly |
| Widget discovery | 6 | Many widgets; naming + docs scatter |
| Layout ergonomics | 6 | Powerful but verbose nesting |
| Theme ergonomics | 5 | Class-map mental model; no CSS compile story |
| Forms & interactivity | 5 | SSR forms easy; PE wiring hard |
| Docs as teacher | 5 | Reference-quality, not tutorial-quality |
| **Overall DX** | **6 / 10** | Strong for fluent UI; weak as app framework onboarding |

Documentation quality score (separate): **6 / 10** — accurate reference docs, missing tutorials and app recipes.

---

## Top documentation improvements (preview)

Full Top 20 lives in `validation/REPORT.md`. Highest priority:

1. End-to-end “Build a blog in 30 minutes” tutorial.
2. Single **Getting Started** index linking CLI → Document → Layout → first page.
3. App shell recipe (assets + runtime + Scaffold).
4. Routing patterns (even if “bring your own”).
5. Interactive PE cookbook (Toast, Modal, Tabs) with copy-paste.
6. Admin preset or documented `--type=` for dashboards.
7. Explicit “auth is out of scope” + recommended integration notes.
8. Explain leftover preset stubs or stop generating unused pages.
9. Path-repository / monorepo install warnings.
10. Production asset story (no CDN Tailwind by default).

---

## Intentional non-fixes

Per validation rules: gaps are **recorded**, not implemented. Framework `src/` was not changed for DX polish during app construction.
