<?php

declare(strict_types=1);

namespace Serixa\OutlookCalendar\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\OutlookCalendar\FakeOutlookCalendarClient;
use Serixa\OutlookCalendar\OutlookCalendarProvider;

final class OutlookCalendarProviderTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $provider = OutlookCalendarProvider::make(new FakeOutlookCalendarClient());
        $created = $provider->create(new CalendarEvent('Meet', '2026-07-23T14:00:00', '2026-07-23T15:00:00'));
        self::assertSame('Meet', $provider->get($created->id)?->title);
    }
}
