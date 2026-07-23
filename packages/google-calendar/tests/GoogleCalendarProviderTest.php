<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\FakeGoogleCalendarClient;
use Serixa\GoogleCalendar\GoogleCalendarProvider;
use Serixa\GoogleCalendar\MemoryCalendar;

final class GoogleCalendarProviderTest extends TestCase
{
    public function testMemoryCalendar(): void
    {
        $cal = new MemoryCalendar();
        $created = $cal->create(new CalendarEvent('Standup', '2026-07-23T09:00:00', '2026-07-23T09:30:00'));
        self::assertNotSame('', $created->id);
        self::assertCount(1, $cal->list());
    }

    public function testFakeClientRoundTrip(): void
    {
        $provider = GoogleCalendarProvider::make(new FakeGoogleCalendarClient());
        $created = $provider->create(new CalendarEvent('Demo', '2026-07-23T10:00:00', '2026-07-23T11:00:00', 'Serixa'));
        self::assertSame('Demo', $provider->get($created->id)?->title);
        $provider->delete($created->id);
        self::assertNull($provider->get($created->id));
    }
}
