<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\Gauge;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Component\ProgressRing;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = charts_example_document('Revenue', [
    Row::make(
        Icon::make('currency-dollar')->decorative()->size('md'),
        Text::make('Revenue')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('MRR and goal tracking from application-prepared numbers.'),
    LineChart::make()
        ->title('MRR')
        ->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'])
        ->dataset(Dataset::make()->addSeries(Series::make('MRR')->values([32, 35, 38, 41, 44, 48])))
        ->smooth()
        ->summary('Monthly recurring revenue in thousands'),
    Row::make(
        Gauge::make(72)->title('Quota')->min(0)->max(100)->summary('72 percent of sales quota'),
        ProgressRing::make(88)->title('Collections')->summary('88 percent collected'),
    )->gap(4),
]);

echo (new Renderer())->render($document), PHP_EOL;
