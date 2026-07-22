<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\Heatmap;
use Serixa\Charts\Component\RadarChart;
use Serixa\Charts\Component\Sparkline;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = charts_example_document('Analytics', [
    Row::make(
        Icon::make('chart-bar')->decorative()->size('md'),
        Text::make('Analytics')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Product analytics surfaces — data still comes from the application.'),
    RadarChart::make()
        ->title('Engagement profile')
        ->labels(['Retention', 'Activation', 'Referral', 'Revenue', 'Satisfaction'])
        ->dataset(
            Dataset::make()
                ->addSeries(Series::make('This week')->values([4, 5, 3, 4, 5]))
                ->addSeries(Series::make('Last week')->values([3, 4, 4, 3, 4])),
        )
        ->legend(),
    Heatmap::make()
        ->title('Feature usage')
        ->labels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri'])
        ->dataset(
            Dataset::make()
                ->addSeries(Series::make('Search')->values([2, 4, 3, 5, 4]))
                ->addSeries(Series::make('Export')->values([1, 2, 2, 3, 2]))
                ->addSeries(Series::make('Share')->values([3, 3, 4, 4, 5])),
        ),
    Sparkline::make()
        ->title('Error rate')
        ->labels(range(1, 14))
        ->dataset(Dataset::make()->addSeries(Series::make('Errors')->values([2, 1, 3, 2, 4, 1, 1, 2, 3, 2, 1, 2, 1, 1]))),
]);

echo (new Renderer())->render($document), PHP_EOL;
