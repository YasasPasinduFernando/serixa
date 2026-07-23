<?php

declare(strict_types=1);

namespace Serixa\Ical\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\Ical\FakeIcalClient;
use Serixa\Ical\IcalCalendarProvider;

final class IcalCalendarProviderTest extends TestCase
{
    public function testFakeRoundTripAndExport(): void
    {
        $client = new FakeIcalClient();
        $provider = IcalCalendarProvider::make($client);
        $provider->create(new CalendarEvent('Export Me', '2026-07-23T16:00:00', '2026-07-23T17:00:00'));
        self::assertStringContainsString('BEGIN:VEVENT', $provider->export());
        self::assertCount(1, $provider->list());
    }
}
