<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Caldav\CaldavCalendarProvider;
use Serixa\Caldav\FakeCaldavClient;
use Serixa\GoogleCalendar\CalendarEvent;

$e = CaldavCalendarProvider::make(new FakeCaldavClient())
    ->create(new CalendarEvent('Smoke', '2026-07-23T12:00:00', '2026-07-23T13:00:00'));
echo $e->id !== '' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($e->id !== '' ? 0 : 1);
