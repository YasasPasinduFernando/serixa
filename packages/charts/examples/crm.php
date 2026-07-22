<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\Timeline;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = charts_example_document('CRM', [
    Row::make(
        Icon::make('user')->decorative()->size('md'),
        Text::make('CRM')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Account activity and milestones — still UI-only.'),
    BarChart::make()
        ->title('Touches by owner')
        ->labels(['Alex', 'Blair', 'Casey', 'Drew'])
        ->dataset(Dataset::make()->addSeries(Series::make('Touches')->values([34, 28, 41, 22]))),
    Timeline::make()
        ->title('Account timeline')
        ->events([
            ['label' => 'Intro call', 'at' => 0],
            ['label' => 'Demo', 'at' => 2],
            ['label' => 'Proposal', 'at' => 5],
            ['label' => 'Closed won', 'at' => 8],
        ])
        ->summary('Key CRM milestones'),
]);

echo (new Renderer())->render($document), PHP_EOL;
