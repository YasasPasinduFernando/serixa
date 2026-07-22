<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\BubbleChart;
use Serixa\Charts\Component\ScatterChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Point;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = charts_example_document('Ecommerce', [
    Row::make(
        Icon::make('building-storefront')->decorative()->size('md'),
        Text::make('Ecommerce')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Catalog and conversion visuals from mapped order data.'),
    BarChart::make()
        ->title('Orders by category')
        ->labels(['Apparel', 'Home', 'Electronics', 'Beauty'])
        ->dataset(Dataset::make()->addSeries(Series::make('Orders')->values([210, 160, 140, 95]))),
    ScatterChart::make()
        ->title('Price vs units')
        ->dataset(
            Dataset::make()->addSeries(
                Series::make('SKUs')->values([
                    Point::make(12, 40),
                    Point::make(24, 28),
                    Point::make(36, 18),
                    Point::make(48, 12),
                ]),
            ),
        ),
    BubbleChart::make()
        ->title('Margin bubbles')
        ->dataset(
            Dataset::make()->addSeries(
                Series::make('Products')->values([
                    Point::make(10, 20, 8),
                    Point::make(30, 35, 14),
                    Point::make(50, 15, 10),
                ]),
            ),
        ),
]);

echo (new Renderer())->render($document), PHP_EOL;
