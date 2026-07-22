<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Calendar\Component\Calendar;
use Serixa\Calendar\Component\DatePicker;
use Serixa\Calendar\Component\TimePicker;
use Serixa\Calendar\Event\Event;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = calendar_example_document('Booking', [
    Row::make(
        Icon::make('calendar')->decorative()->size('md'),
        Text::make('Booking')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Appointment booking UI — app stores the selected slot.'),
    Calendar::make()
        ->title('Studio availability')
        ->focus('2026-07-01')
        ->today('2026-07-22')
        ->events([
            Event::make('Booked')->start('2026-07-22 10:00')->end('2026-07-22 11:00')->color('#64748b'),
            Event::make('Open')->start('2026-07-23 14:00')->end('2026-07-23 15:00')->color('#16a34a'),
        ])
        ->legend([
            ['label' => 'Booked', 'color' => '#64748b'],
            ['label' => 'Open', 'color' => '#16a34a'],
        ]),
    Row::make(
        DatePicker::make('appointment_date')->label('Date')->value('2026-07-23'),
        TimePicker::make('appointment_time')->label('Time')->value('14:00'),
    )->gap(4),
]);

echo (new Renderer())->render($document), PHP_EOL;
