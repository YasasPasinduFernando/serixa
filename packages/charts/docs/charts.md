# SERIXA Charts

`serixa/charts` is the official UI-only chart package for SERIXA.

It renders charts from data you already have. It does **not** implement analytics,
business logic, database querying, or reporting engines.

Depends on `serixa/framework` and `serixa/icons`. Starter kits and applications sit
above this package.

## Architecture

### Why charts belong in a package

Charts are an optional product surface. Many SERIXA apps never need them. Keeping
them in `serixa/charts` lets applications and starter kits opt in without bloating
the core framework install, release cadence, or theme surface.

### Why charts must not live in `serixa/framework`

Framework owns document, layout, controls, theme contracts, and progressive
enhancement primitives. Chart geometry, SVG builders, dataset normalization, and
chart-specific theme keys (`charts.*`) are product widgets. Putting them in
framework would couple every consumer to visualization code they may never use
and force breaking theme/API churn for chart fixes.

### How developers plug in data

The package accepts data; the host app decides the source:

| Source | How |
|--------|-----|
| Static arrays | `->labels([...])->dataset(Dataset::make()->addSeries(...))` |
| Collections / Traversable | Pass any `Traversable` or generator into `Normalizer` / `Series::values()` |
| JSON | Pass a JSON array string to `Normalizer::numbers()` / `labels()`, or decode first |
| Database / API | Query or fetch in the application, then map rows to labels + series values |
| CSV | Parse in the application, then pass arrays |

Example:

```php
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;

LineChart::make()
    ->title('Revenue')
    ->labels(['Jan', 'Feb', 'Mar'])
    ->dataset(
        Dataset::make()->addSeries(
            Series::make('Sales')->values([12, 18, 9]),
        ),
    )
    ->smooth()
    ->legend();
```

Callable datasets are also supported:

```php
->dataset(function (Dataset $d): void {
    $d->labels($months)->addSeries(Series::make('MRR')->values($values));
})
```

### SSR strategy

1. Server renders a semantic `<figure>` with title/caption.
2. Inside: an SVG (visual) plus an accessible HTML `<table>` fallback of the same data.
3. Attributes (`data-serixa-chart`, `data-serixa-chart-type`, `data-serixa-chart-config`) mark the figure for enhancement.
4. Without JavaScript the SVG and table remain readable.

### Progressive enhancement strategy

1. Load framework `serixa.js`, then `assets/charts.js` (both deferred, in that order).
2. `ChartRuntime::register($assets)` adds the charts script URL.
3. `Serixa.module('chart')` enhances the existing SSR SVG (hover/focus, keyboard, aria-live).
4. **No hydration. No virtual DOM.** Markup is not replaced by a client tree.

## Chart catalog

| Type | Class | Notes |
|------|--------|--------|
| Line | `LineChart` | `smooth()`, series markers |
| Bar | `BarChart` | Categorical bars |
| Area | `AreaChart` | Filled line, `smooth()` |
| Pie | `PieChart` | Single series slices |
| Donut | `DonutChart` | Pie with hole |
| Radar | `RadarChart` | Multi-axis polygon |
| Polar | `PolarChart` | Polar area |
| Scatter | `ScatterChart` | Points |
| Bubble | `BubbleChart` | Points with radius |
| Sparkline | `Sparkline` | Compact trend |
| Progress ring | `ProgressRing` | `ProgressRing::make(72)` |
| Gauge | `Gauge` | `Gauge::make(55)`, optional zones |
| Timeline | `Timeline` | Sequential events |
| Heatmap | `Heatmap` | Series × labels intensity grid |

### Shared pieces

`Chart`, `Dataset`, `Series`, `Axis` / `XAxis` / `YAxis`, `Grid`, `Legend`, `Tooltip`,
`Marker`, `Threshold`, `Annotation`.

## Theme

Decorator only — wrap the host theme:

```php
use Serixa\Charts\Theme\ChartsTheme;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::set(new ChartsTheme(new DefaultTheme()));
```

Keys live under `charts.*` (for example `charts.root`, `charts.svg`, `charts.table`,
`charts.axis`, `charts.grid`, `charts.legend`). Widgets do **not** embed Tailwind
utilities; classes come from the theme map.

## Runtime

```php
use Serixa\Charts\Runtime\ChartRuntime;

$document->assets()->js('/assets/serixa.js', ['defer' => true]);
ChartRuntime::register($document->assets(), '/assets/charts/charts.js');
// Host must serve ChartRuntime::scriptPath() at that public URL.
```

## Accessibility

- Figure + optional `summary()` for screen readers
- SVG `role="img"`; table carries the data when fallback is on
- `aria-live` region for enhancement announcements
- Keyboard focus on interactive points when JS is present
- High-contrast classes via theme tokens
- Prefer visible or `tableSrOnly()` table fallback rather than removing data

## Icons

Any decorative or labeled icons in chart UIs must come from `serixa/icons`
(`Serixa\Icons\Component\Icon`). Do not ship ad-hoc icon SVGs inside this package.

## Performance tips

- Prefer server SVG for dashboards; keep series counts modest for SSR HTML size.
- Disable table fallback only when another accessible representation exists.
- Sparklines for dense grids; full charts for primary insights.
- Defer charts.js; do not bundle a charting SPA framework.

## Examples

See `packages/charts/examples/` — dashboard, analytics, sales, visitors, revenue, CRM, ecommerce.

```bash
cd packages/charts
php examples/dashboard.php
```

## Package layout

```
packages/charts/
  src/Component|Dataset|Axis|Legend|Theme|Contracts|Support|Exceptions|Runtime/
  assets/charts.js
  docs/
  tests/
  examples/
```
