<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Calendar\Component\AgendaView;
use Serixa\Calendar\Component\MiniCalendar;
use Serixa\Calendar\Event\Event;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = calendar_example_document('Personal agenda', [
    Row::make(
        Icon::make('user')->decorative()->size('md'),
        Text::make('Personal agenda')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Month glance plus agenda list.'),
    Row::make(
        MiniCalendar::make()
            ->focus('2026-07-01')
            ->today('2026-07-22')
            ->selected('2026-07-22')
            ->events([
                Event::make('Dentist')->start('2026-07-22 09:00'),
                Event::make('Dinner')->start('2026-07-24 19:00'),
            ]),
        AgendaView::make()
            ->title('This month')
            ->focus('2026-07-01')
            ->today('2026-07-22')
            ->events([
                Event::make('Dentist')->start('2026-07-22 09:00')->end('2026-07-22 10:00')->description('Downtown clinic'),
                Event::make('Dinner')->start('2026-07-24 19:00')->end('2026-07-24 21:00'),
                Event::make('Flight')->start('2026-07-30')->allDay(),
            ]),
    )->gap(4),
]);

echo (new Renderer())->render($document), PHP_EOL;
