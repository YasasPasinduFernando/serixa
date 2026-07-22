<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\AreaChart;
use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\DonutChart;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Component\ProgressRing;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

$document = charts_example_document('Dashboard', [
    Row::make(
        Icon::make('home')->decorative()->size('md'),
        Text::make('Dashboard')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('SSR charts from static arrays — plug in DB/API in the host app.'),
    LineChart::make()
        ->title('Revenue trend')
        ->labels($months)
        ->dataset(Dataset::make()->addSeries(Series::make('Revenue')->values([42, 48, 51, 47, 55, 62])))
        ->smooth()
        ->legend()
        ->summary('Revenue by month'),
    Row::make(
        BarChart::make()
            ->title('Signups')
            ->labels($months)
            ->dataset(Dataset::make()->addSeries(Series::make('Users')->values([120, 140, 110, 160, 180, 200])))
            ->width(420)
            ->height(240),
        DonutChart::make()
            ->title('Plan mix')
            ->labels(['Free', 'Pro', 'Team'])
            ->dataset(Dataset::make()->addSeries(Series::make('Plans')->values([55, 30, 15])))
            ->width(280)
            ->height(240),
        ProgressRing::make(78)->title('Goal')->summary('78 percent of quarterly goal'),
    )->gap(4),
    AreaChart::make()
        ->title('Sessions')
        ->labels($months)
        ->dataset(Dataset::make()->addSeries(Series::make('Sessions')->values([900, 980, 1050, 990, 1120, 1250])))
        ->smooth(),
]);

echo (new Renderer())->render($document), PHP_EOL;
