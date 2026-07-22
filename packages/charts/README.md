# SERIXA Charts

Official charts package for SERIXA — SSR SVG and table charts with progressive
enhancement. UI only: no analytics engine, no database querying, no reporting.

## Requirements

- PHP 8.2+
- `serixa/framework`
- `serixa/icons`

## Installation (monorepo)

```bash
cd packages/charts
composer install
composer qa
```

## Quick start

```php
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Theme\ChartsTheme;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::set(new ChartsTheme(new DefaultTheme()));

$chart = LineChart::make()
    ->labels(['Jan', 'Feb', 'Mar'])
    ->dataset(
        Dataset::make()->addSeries(Series::make('Sales')->values([12, 18, 9])),
    )
    ->smooth()
    ->legend();
```

Load `serixa.js` then `ChartRuntime::register(...)` for progressive enhancement.

See [docs/charts.md](docs/charts.md) for architecture, catalog, SSR/PE, and a11y.
