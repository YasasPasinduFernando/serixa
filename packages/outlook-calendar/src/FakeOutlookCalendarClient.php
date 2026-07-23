<?php

declare(strict_types=1);

namespace Serixa\OutlookCalendar;

use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\MemoryCalendar;
use Serixa\OutlookCalendar\Contracts\OutlookCalendarClient;

final class FakeOutlookCalendarClient implements OutlookCalendarClient
{
    private MemoryCalendar $memory;

    public function __construct(?MemoryCalendar $memory = null)
    {
        $this->memory = $memory ?? new MemoryCalendar();
    }

    public function createEvent(array $event): array
    {
        return $this->memory->create(CalendarEvent::fromArray($event))->toArray();
    }

    public function getEvent(string $eventId): ?array
    {
        return $this->memory->get($eventId)?->toArray();
    }

    public function deleteEvent(string $eventId): void
    {
        $this->memory->delete($eventId);
    }

    public function listEvents(?string $from = null, ?string $to = null): array
    {
        return array_map(static fn (CalendarEvent $e): array => $e->toArray(), $this->memory->list($from, $to));
    }
}
