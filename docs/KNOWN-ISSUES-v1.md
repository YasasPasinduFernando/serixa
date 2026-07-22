# Known issues — SERIXA v1.0 candidate

1. **Dual CSS files** — `serixa build` writes both `public/assets/serixa.css` and a BC alias `public/assets/app.css`. Prefer registering `serixa.css` only.
2. **Utility + semantic styling** — `DefaultTheme` still emits many Tailwind utility class names; Theme Compiler adds `.sx-*` recipes. Full migration of every theme map to semantic classes is deferred post-v1.
3. **Skeleton starters** — crm / ecommerce / portfolio / docs are scaffolds (index placeholder), not full apps like blog/admin.
4. **No first-party router / ORM / auth** — intentional; auth-ui is presentation only.
5. **Package Html helpers** — charts/calendar/devtools still ship local `Support\Html` wrappers; consolidation is non-blocking.
6. **Benchmark variance** — `tools/benchmarks/v1-performance.php` numbers are machine-local.
7. **Website color `!important`** — limited to brand/button text color overrides in docs chrome CSS (not layout width).

None of the above block a v1.0.0 release candidate freeze, but should be tracked for v1.1 polish.
