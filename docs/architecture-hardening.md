# Architecture review — production hardening

Date: 2026-07-22 · Baseline: v1.0.0-rc4

Full Phase 1 review (read-only) completed before code changes. Summary:

## Ranked hardening backlog

1. Split DefaultTheme (deferred — large BC-safe refactor)
2. Unify ThemeCompiler ↔ DefaultTheme tokens — **improved** (canonical typography aliases + ColorToken hover/onPrimary)
3. WidgetInterface child typing on Card/Container — **done**
4. Core CLI doctor / inspect / benchmark / profile / cache:clear — **done**
5. Document pipeline N runs — mitigated via shared `RenderPipeline::default()`
6. AccessibilityPass inventory — **done** (stats metadata + AriaContract tests)
7. ClassMerger / ThemeCompiler caches — **done**
8. Dual CSS alias — still BC; documented
9. Layout Flexible / wrap — deferred (additive APIs later)
10. Plugin pipeline contributors — deferred

## Coverage snapshot

Monorepo PHPUnit (framework + packages + starters + website): **433+ tests / 4000+ assertions**.

## Conventions

- Prefer `WidgetInterface` in type hints
- Prefer `Serixa\Styling\StyleSheet`
- Prefer `fill()` / `Expanded` for layout width
- Run `serixa doctor` + `serixa build` before deploy
- Use `serixa profile` / `serixa benchmark` for local performance checks
