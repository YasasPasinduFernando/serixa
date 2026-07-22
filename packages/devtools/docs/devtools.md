# SERIXA DevTools

`serixa/devtools` is the official developer tools package for SERIXA — inspectors,
collectors, overlay UI, and CLI commands similar in spirit to Laravel Debugbar or
Symfony Profiler.

**Development only. Never ship to production.**

Depends on `serixa/framework` and `serixa/cli`. Framework does **not** depend on this package.

## Architecture

### Why DevTools belongs in a package

Profiling UI, collectors, and overlays are optional DX. Most production deploys
must not load them. A dedicated package lets apps opt in via Composer `require-dev`.

### Why DevTools must never live in `serixa/framework`

Framework must render perfectly without DevTools. Putting collectors/overlays in
framework would risk production bloat and couple every consumer to DX UI. Framework
already exposes additive contracts (`TreeInspector`, `WidgetInspector`,
`RenderProfiler`, `Renderer::tree()`); this package implements and consumes them.

### How production builds exclude it

1. Install as `require-dev` only (`composer install --no-dev` omits it).
2. Never call `DevTools::enable()` when `APP_ENV=production`.
3. When disabled: `collect()` → `[]`, `injectHtml()` → identity, `attach()` → no-op.
4. Near-zero overhead: a boolean check only — no collector registration until enable/boot.

### How applications enable it

```php
use Serixa\DevTools\DevTools;

if (getenv('APP_ENV') !== 'production') {
    DevTools::enable();
    DevTools::attach($document);          // registers CSS/JS
}

$html = $renderer->render($document);
echo DevTools::injectHtml($html);         // injects overlay before </body> when enabled
```

CLI:

```php
DevTools::registerCli(); // serixa inspect|profile|doctor|benchmark
```

### Runtime architecture

1. **Collectors** gather metrics into a payload when enabled.
2. **Inspectors** build widget/theme/document/asset/plugin trees.
3. **Panels** render SSR HTML tabs from that payload.
4. **Overlay** appends toolbar + JSON (`#serixa-devtools-data`) — does not rewrite app markup.
5. **devtools.js** enhances tabs, Ctrl+Shift+D toggle, Alt+hover element inspector.

## Inspector architecture

| Inspector | Focus |
|-----------|--------|
| Widget / Render / Component tree | Widget composition |
| Theme tree | Theme decorator chain |
| Document tree | Document shell |
| Asset tree | Registered assets |
| Plugin tree | Optional PluginManager via context extras |

Also implements framework `WidgetInspector` / `TreeInspector` (`DefaultWidgetInspector`, `DefaultTreeInspector`).

## Collector architecture

Lazy registry. Collectors run only inside `DevTools::collect()` when enabled:

Render time, memory, widget count, theme resolution, assets, event timeline,
progressive enhancement hints, accessibility heuristics, CLI command list.

## Developer API

```php
DevTools::enable();
DevTools::disable();
DevTools::collect($ctx);
DevTools::panel('overview');
DevTools::overlay(true);
DevTools::attach($document);
DevTools::injectHtml($html);
DevTools::profile(fn () => $renderer->render($w));
DevTools::registerCli();
```

## CLI summary

| Command | Purpose |
|---------|---------|
| `serixa inspect` | Environment / theme / memory |
| `serixa profile` | Single render timing (`--json`) |
| `serixa doctor` | Health checks (exit 1 on FAIL) |
| `serixa benchmark` | N renders avg/min/max (`--times=N`) |

Register via `DevTools::registerCli()` (or `CliRegistrar::register()`).

## Accessibility

- Toolbar `role="complementary"` / toolbar / `aria-selected` tabs
- `aria-live` announcements
- Ctrl+Shift+D toggle
- High-contrast slate/sky overlay styles

## SSR

DevTools does not alter widget HTML. When enabled it **only injects** its own
aside + assets. When disabled, output is unchanged.

## Examples

```bash
cd packages/devtools
php examples/demo.php
php examples/disabled.php
```
