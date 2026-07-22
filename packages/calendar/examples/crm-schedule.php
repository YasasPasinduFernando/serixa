<?php

declare(strict_types=1);

require __DIR__ . '/_bootstrap.php';

use Serixa\Calendar\Component\Scheduler;
use Serixa\Calendar\Event\Event;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$document = calendar_example_document('CRM schedule', [
    Row::make(
        Icon::make('briefcase')->decorative()->size('md'),
        Text::make('CRM schedule')->as('h1')->size('xl')->weight('bold'),
    )->gap(2)->alignCenter(),
    Text::make('Account touches and demos for the week.'),
    Scheduler::make()
        ->title('Pipeline week')
        ->mode('week')
        ->focus('2026-07-20')
        ->today('2026-07-22')
        ->events([
            Event::make('Acme demo')->start('2026-07-22 11:00')->end('2026-07-22 12:00')->resource('alex'),
            Event::make('Follow-up')->start('2026-07-23 09:30')->end('2026-07-23 10:00')->resource('blair'),
        ])
        ->resources([
            ['id' => 'alex', 'label' => 'Alex'],
            ['id' => 'blair', 'label' => 'Blair'],
        ]),
]);

echo (new Renderer())->render($document), PHP_EOL;
