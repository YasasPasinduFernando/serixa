<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Calendar\Component\TimelineCalendar;
use Serixa\Calendar\Event\Event;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = calendar_example_document('Project timeline', [
    Row::make(
        Icon::make('chart-bar')->decorative()->size('md'),
        Text::make('Project timeline')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Milestone bars for the current month — data from the app.'),
    TimelineCalendar::make()
        ->title('Launch program')
        ->focus('2026-07-01')
        ->today('2026-07-22')
        ->events([
            Event::make('Design')->start('2026-07-01')->end('2026-07-10')->color('#0ea5e9'),
            Event::make('Build')->start('2026-07-08')->end('2026-07-20')->color('#22c55e'),
            Event::make('QA')->start('2026-07-18')->end('2026-07-28')->color('#f59e0b'),
        ]),
]);

echo (new Renderer())->render($document), PHP_EOL;
