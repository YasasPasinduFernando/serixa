<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar;

use Serixa\GoogleCalendar\Contracts\GoogleCalendarClient;

final class FakeGoogleCalendarClient implements GoogleCalendarClient
{
    private MemoryCalendar $memory;

    public function __construct(?MemoryCalendar $memory = null)
    {
        $this->memory = $memory ?? new MemoryCalendar();
    }

    public function insert(string $calendarId, array $event): array
    {
        $created = $this->memory->create(CalendarEvent::fromArray($event));

        return $created->toArray();
    }

    public function get(string $calendarId, string $eventId): ?array
    {
        $event = $this->memory->get($eventId);

        return $event?->toArray();
    }

    public function delete(string $calendarId, string $eventId): void
    {
        $this->memory->delete($eventId);
    }

    public function list(string $calendarId, ?string $from = null, ?string $to = null): array
    {
        return array_map(
            static fn (CalendarEvent $e): array => $e->toArray(),
            $this->memory->list($from, $to),
        );
    }
}
