<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\AreaChart;
use Serixa\Charts\Component\PieChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = charts_example_document('Visitors', [
    Row::make(
        Icon::make('users')->decorative()->size('md'),
        Text::make('Visitors')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Traffic overview — JSON or Traversable inputs normalize the same way.'),
    AreaChart::make()
        ->title('Daily visitors')
        ->labels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
        ->dataset(
            Dataset::make()
                ->addSeries(Series::make('Unique')->values([420, 510, 480, 530, 610, 390, 360]))
                ->addSeries(Series::make('Returning')->values([180, 200, 190, 210, 240, 150, 140])),
        )
        ->smooth()
        ->legend(),
    PieChart::make()
        ->title('Channels')
        ->labels(['Organic', 'Paid', 'Referral', 'Direct'])
        ->dataset(Dataset::make()->addSeries(Series::make('Share')->values([40, 25, 20, 15]))),
]);

echo (new Renderer())->render($document), PHP_EOL;
