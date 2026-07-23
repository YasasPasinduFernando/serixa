<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\Ical\FakeIcalClient;
use Serixa\Ical\IcalCalendarProvider;

$p = IcalCalendarProvider::make(new FakeIcalClient());
$p->create(new CalendarEvent('Smoke', '2026-07-23T12:00:00', '2026-07-23T13:00:00'));
$ok = str_contains($p->export(), 'BEGIN:VEVENT');
echo $ok ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($ok ? 0 : 1);
