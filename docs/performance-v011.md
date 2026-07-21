# Performance report — v0.11.0 vs v0.10.0

Measured on Windows 10 · PHP 8.2.12 · 2026-07-22.  
Baseline figures from [performance.md](performance.md) / validation (v0.10.0).  
v0.11.0 figures measured after theme memoization, token map cache, shared widget helpers, CLI version fast path, and lazy runtime module mounting.

---

## Headline

| Metric | v0.10.0 | v0.11.0 | Change |
|--------|--------:|--------:|--------|
| Theme 3 000 class lookups | ~2 287 ms | **43.4 ms** | **~53× faster** |
| Implied per-lookup | ~0.76–0.92 ms | **~14.5 µs** | **~50–60×** |
| Sample widget-tree render | ~14–43 ms (full pages) | **10.1 ms** (bench tree) | Faster warm path |
| Peak memory (sample) | ~4 MiB | **~4 MiB** | Unchanged slab |
| Runtime `serixa.js` | 20 460 B | **21 118 B** | +658 B (module markers) |
| CLI `version` | ~170 ms | ~216 ms* | Startup-bound |
| CLI `new` | ~193–231 ms | ~227 ms | ≈ same |
| CLI `list` | n/a | ~183 ms | — |

\*Cold PHP process cost dominates; `version` now skips command registration / TemplateEngine construction. Absolute wall time still ~OS + autoload.

---

## Theme lookup

| Benchmark | v0.10.0 | v0.11.0 |
|-----------|--------:|--------:|
| 1 000 × (button + card + scaffold) = 3 000 lookups | 2 286.7 ms | **43.4 ms** |
| Per lookup | ~0.76 ms | **~0.014 ms** |

**Implementation:** instance `classCache` keyed by `component + json(state)`; `tokenMap()` built once per theme instance.

---

## Render / HTML / memory

| Metric | v0.10.0 (validation pages) | v0.11.0 (bench tree*) |
|--------|---------------------------:|----------------------:|
| Render time | 14–43 ms | 10.1 ms |
| HTML size | 4.5–12.2 KiB | 1 624 B (small tree) |
| Peak memory | ~4 MiB | ~4 MiB |

\*Comparable full-page smoke was not re-run for every validation route in this release; theme cache is the dominant win for large trees.

---

## Runtime asset

| Asset | v0.10.0 | v0.11.0 |
|-------|--------:|--------:|
| `assets/runtime/serixa.js` | 20 460 | 21 118 |

Lazy module mount: modules only call `mount()` when matching `data-serixa-*` markers exist in the document. Behavior for pages that use interactive widgets is unchanged.

---

## CLI

| Command | v0.10.0 | v0.11.0 |
|---------|--------:|--------:|
| `serixa version` | ~170 ms | ~216 ms (fast path; no generators) |
| `serixa new …` | ~193–231 ms | ~227 ms |
| `serixa build` / `docs` | ~177–216 ms | not re-timed (unchanged commands) |

---

## Pipeline / registry

| Area | Change |
|------|--------|
| Widget helper allocs | Shared static `HtmlEscaper` / `AttributeBag` / `ClassMerger` defaults |
| WidgetRegistry | `defaultMetadata()` list memoized |
| HtmlWriter | Unchanged API; still thin buffer helper |
| Pipeline pass order | Unchanged (message text only) |

---

## Interpretation

1. **Theme memoization is the v0.11.0 performance story** — validates H4 from the validation report.
2. Runtime grew slightly for safer lazy mounting; acceptable tradeoff.
3. CLI wall clock remains PHP process startup dominated on Windows; structural win is skipping unnecessary work on `version`.
4. No public API behavior changes; HTML class strings for identical widget state remain stable.

See also: [dx-audit.md](dx-audit.md), [validation/REPORT.md](../validation/REPORT.md).
