<?php

declare(strict_types=1);

namespace Serixa\Caldav;

use Serixa\Caldav\Contracts\CaldavClient;
use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\MemoryCalendar;

final class FakeCaldavClient implements CaldavClient
{
    private MemoryCalendar $memory;

    public function __construct(?MemoryCalendar $memory = null)
    {
        $this->memory = $memory ?? new MemoryCalendar();
    }

    public function putEvent(string $calendarHref, array $event): array
    {
        return $this->memory->create(CalendarEvent::fromArray($event))->toArray();
    }

    public function getEvent(string $calendarHref, string $eventId): ?array
    {
        return $this->memory->get($eventId)?->toArray();
    }

    public function deleteEvent(string $calendarHref, string $eventId): void
    {
        $this->memory->delete($eventId);
    }

    public function report(string $calendarHref, ?string $from = null, ?string $to = null): array
    {
        return array_map(static fn (CalendarEvent $e): array => $e->toArray(), $this->memory->list($from, $to));
    }
}
