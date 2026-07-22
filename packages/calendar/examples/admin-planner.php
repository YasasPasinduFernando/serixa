<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Calendar\Component\ResourceCalendar;
use Serixa\Calendar\Component\Scheduler;
use Serixa\Calendar\Event\Event;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = calendar_example_document('Admin planner', [
    Row::make(
        Icon::make('building-office')->decorative()->size('md'),
        Text::make('Admin planner')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Room and desk planning for facilities admins.'),
    Scheduler::make()
        ->title('Facilities')
        ->mode('day')
        ->focus('2026-07-22')
        ->today('2026-07-22')
        ->events([
            Event::make('Board')->start('2026-07-22 09:00')->end('2026-07-22 11:00')->resource('room-a'),
            Event::make('Interview')->start('2026-07-22 13:00')->end('2026-07-22 14:00')->resource('room-b'),
        ])
        ->resources([
            ['id' => 'room-a', 'label' => 'Room A'],
            ['id' => 'room-b', 'label' => 'Room B'],
        ]),
    ResourceCalendar::make()
        ->title('Rooms today')
        ->focus('2026-07-22')
        ->today('2026-07-22')
        ->resources([
            ['id' => 'room-a', 'label' => 'Room A'],
            ['id' => 'room-b', 'label' => 'Room B'],
        ])
        ->events([
            Event::make('Board')->start('2026-07-22 09:00')->end('2026-07-22 11:00')->resource('room-a'),
            Event::make('Interview')->start('2026-07-22 13:00')->end('2026-07-22 14:00')->resource('room-b'),
        ]),
]);

echo (new Renderer())->render($document), PHP_EOL;
