# SERIXA Performance Report (v0.10.0 validation)

Measured on Windows 10 · PHP 8.2.12 · 2026-07-21.  
Validation apps: `validation/blog`, `validation/admin-dashboard`, `validation/crm`.  
Renderer: `Serixa\Rendering\Renderer` (SSR HTML fragment/document). Runtime asset: `assets/runtime/serixa.js`.

> Theme model is Tailwind class maps (`DefaultTheme::classes`), not a CSS compiler. “Theme compilation” is reported as class-resolution cost.

---

## Summary

| Metric | Result |
|--------|--------|
| Runtime JS | **20 460 bytes** (~20.0 KiB) |
| Total `assets/` tree | **1 file / 20 460 bytes** |
| Typical page HTML | **4.5–12.2 KiB** |
| Typical render | **14–43 ms** cold-ish process |
| Peak PHP memory (sample pages) | **~4 MiB** |
| CLI `version` (avg ×5) | **~170 ms** |
| CLI `new` | **~193–231 ms** |
| CLI `build` | **~206–216 ms** |
| CLI `docs` | **~177–189 ms** |
| `composer install` (path repo app) | **~6.4 s** |

---

## Render time & HTML size

| App / page | Render (ms) | HTML (bytes) | Peak memory |
|------------|------------:|-------------:|------------:|
| blog / home | 42.7 | 5 691 | 4 194 304 |
| blog / articles | 20.4 | 8 233 | 4 194 304 |
| blog / article | 14.2 | 4 593 | 4 194 304 |
| admin / dashboard | 30.8 | 9 152 | 4 194 304 |
| admin / crud | 25.0 | 9 283 | 4 194 304 |
| admin / users | 15.6 | 8 259 | 4 194 304 |
| crm / dashboard | 20.2 | 9 161 | 4 194 304 |
| crm / customers | 32.8 | 12 204 | 4 194 304 |
| crm / invoices | 21.2 | 9 795 | 4 194 304 |

Notes:

- First page in a process pays autoload + theme warm-up (blog home ~43 ms); subsequent pages in the same process are faster.
- Memory deltas are coarse (`memory_get_usage(true)` / OS pages); real incremental cost is small relative to 2 MiB slabs.
- HTML sizes are renderer output only (includes Tailwind CDN script tags where layouts register them).

---

## Memory usage

| Observation | Value |
|-------------|------:|
| PHPUnit suite peak (framework) | 12.00 MB |
| Validation page peak (Renderer) | ~4 MB |
| CS Fixer analysis | 34 MB |

No sustained leak signal in short render loops. Long-lived PHP-FPM / worker profiling was **not** run in this milestone.

---

## Generated document / HTML size

| Sample | Bytes | Notes |
|--------|------:|-------|
| Blog home | 5 691 | Scaffold + nav + cards |
| Blog articles (list + pagination) | 8 233 | Heavier list page |
| Admin dashboard | 9 152 | Stats + activity + tabs |
| CRM customers | 12 204 | Largest measured page |

Document size ≈ HTML size for these apps (layouts wrap `Document` and render to a full HTML string).

---

## Runtime asset size

| Asset | Bytes |
|-------|------:|
| `assets/runtime/serixa.js` | 20 460 |
| Entire `assets/` directory | 20 460 |

Runtime is intentionally small (no VDOM). Interactive widgets rely on PE metadata + modular handlers inside this file.

---

## Theme resolution cost

Not a CSS compile step. Benchmark: `DefaultTheme::classes()` lookups.

| Benchmark | Result |
|-----------|--------|
| 1 000 × (button + card + scaffold) = 3 000 lookups | **2 286.7 ms** |
| Implied single lookup | **~0.76–0.92 ms** |

This is a **High** performance opportunity for hot paths with deep trees (many widgets × many class resolutions).

---

## CLI generation & tooling speed

| Command | Time (ms) |
|---------|----------:|
| `serixa version` (avg ×5) | 170 |
| `serixa new …` | 193–231 |
| `serixa build` | 206–216 |
| `serixa docs` | 177–189 |
| `composer install` in generated app (path repo) | ~6 374 |

CLI itself is fast. Path-repository install dominates first-run DX when apps live beside the framework.

---

## Framework QA suite speed

| Check | Time / result |
|-------|---------------|
| PHPUnit (`composer test` via `qa`) | ~1.2 s · **119 tests OK** |
| PHPStan | **OK** |
| PHP-CS-Fixer (dry) | ~2.0 s · **0 fixes** |

---

## Critical tooling note (measurement hazard)

Embedding validation apps **inside** the framework tree with Composer path junctions makes `vendor/serixa/framework` recurse through `validation/*/vendor` and `playground/*/vendor`. Naive recursive disk scans hang or explode.  

**Workaround used:** count LOC only under app `pages/`, `components/`, `layouts/`, `public/`, `config/`.  

Recorded as Critical DX debt in the validation report (not fixed in this milestone).

---

## Opportunities (summary)

See full prioritized list in `validation/REPORT.md` → Top 20 performance opportunities. Highest leverage items:

1. Cache / memoize theme class maps per component+state key.
2. Avoid repeated `DefaultTheme` work across sibling widgets.
3. Document / ship apps **outside** the monorepo path junction (or use mirror/copy installs for validation).
4. Trim Tailwind CDN dependency for production builds (app concern, but dominates HTML weight).
5. Profile first-render vs warm-render separately in CI.
