<?php

declare(strict_types=1);

namespace Serixa\Caldav\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Caldav\CaldavCalendarProvider;
use Serixa\Caldav\FakeCaldavClient;
use Serixa\GoogleCalendar\CalendarEvent;

final class CaldavCalendarProviderTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $provider = CaldavCalendarProvider::make(new FakeCaldavClient());
        $created = $provider->create(new CalendarEvent('DAV', '2026-07-23T18:00:00', '2026-07-23T19:00:00'));
        self::assertSame('DAV', $provider->get($created->id)?->title);
    }
}
