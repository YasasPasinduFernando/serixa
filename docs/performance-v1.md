# Performance — SERIXA v1.0

Measured on PHP 8.2 (local Windows). Re-run with:

```bash
php tools/benchmarks/v1-performance.php
```

Results are also written to `docs/generated/performance-v1.json` (gitignored when under generated/).

## Summary (representative run)

| Subsystem | Metric | Result |
|-----------|--------|--------|
| Theme Compiler | wall time / rules | ~5 ms / 25 rules |
| Style Compiler | wall time / CSS bytes | ~18 ms / ~38 KB |
| Renderer | avg over 200 small trees | ~25 ms / render* |
| Theme map | avg `classes('button')` × 1000 | ~0.02 ms |
| Peak memory (bench) | | ~4 MB |

\*Includes pipeline + theme resolution for a Card + buttons tree (~1.2 KB HTML). Absolute numbers vary by machine; use them comparatively.

## Architecture notes

- **Compile once, serve static** — Theme Compiler + utility foundation → `public/assets/serixa.css`
- **No runtime Tailwind** — zero network CSS dependency
- **CssOptimizer** merges duplicate selectors before write
- **DefaultTheme** caches class maps per component+state key

## Recommendations

1. Run `serixa build` in CI/deploy; commit or artifact `serixa.css` for apps that skip build on the target host.
2. Prefer `Expanded` + `fill()` over fighting width utilities (layout stability + fewer overrides).
3. Register `ClientRuntime` only on pages that need interactive widgets.
4. Keep widget trees shallow where possible; avoid unnecessary nested `Container` wrappers.

## Historical

Earlier notes: [performance.md](performance.md), [performance-v011.md](performance-v011.md).
