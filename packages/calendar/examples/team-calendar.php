<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Calendar\Component\Calendar;
use Serixa\Calendar\Event\Event;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = calendar_example_document('Team calendar', [
    Row::make(
        Icon::make('users')->decorative()->size('md'),
        Text::make('Team calendar')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Shared team milestones for the month.'),
    Calendar::make()
        ->view('month')
        ->focus('2026-07-01')
        ->today('2026-07-22')
        ->events([
            Event::make('Sprint planning')->start('2026-07-07')->allDay()->color('#7c3aed'),
            Event::make('Retro')->start('2026-07-18')->allDay()->color('#7c3aed'),
            Event::make('All-hands')->start('2026-07-22 16:00')->end('2026-07-22 17:00')->color('#0369a1'),
        ])
        ->summary('Team calendar for July 2026'),
]);

echo (new Renderer())->render($document), PHP_EOL;
