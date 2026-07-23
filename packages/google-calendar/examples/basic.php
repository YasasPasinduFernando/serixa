<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\FakeGoogleCalendarClient;
use Serixa\GoogleCalendar\GoogleCalendarProvider;

$provider = GoogleCalendarProvider::make(new FakeGoogleCalendarClient());
$event = $provider->create(new CalendarEvent('Smoke', '2026-07-23T12:00:00', '2026-07-23T13:00:00'));
echo $event->id !== '' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($event->id !== '' ? 0 : 1);
