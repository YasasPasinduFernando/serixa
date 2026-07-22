<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Legend\Threshold;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = charts_example_document('Sales', [
    Row::make(
        Icon::make('shopping-cart')->decorative()->size('md'),
        Text::make('Sales')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Sales pipeline charts from precomputed arrays.'),
    BarChart::make()
        ->title('Deals by stage')
        ->labels(['Lead', 'Qualified', 'Proposal', 'Won', 'Lost'])
        ->dataset(Dataset::make()->addSeries(Series::make('Deals')->values([48, 32, 21, 14, 9])))
        ->threshold(Threshold::make(20, 'Target')),
    LineChart::make()
        ->title('Close rate')
        ->labels(['W1', 'W2', 'W3', 'W4'])
        ->dataset(
            Dataset::make()
                ->addSeries(Series::make('Close %')->values([22, 25, 28, 31]))
                ->addSeries(Series::make('Target')->values([25, 25, 25, 25])),
        )
        ->smooth()
        ->legend(),
]);

echo (new Renderer())->render($document), PHP_EOL;
