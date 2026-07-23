<?php

declare(strict_types=1);

namespace Serixa\Ical;

use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\MemoryCalendar;
use Serixa\Ical\Contracts\IcalClient;

final class FakeIcalClient implements IcalClient
{
    private MemoryCalendar $memory;

    public function __construct(?MemoryCalendar $memory = null)
    {
        $this->memory = $memory ?? new MemoryCalendar();
    }

    public function addEvent(array $event): array
    {
        return $this->memory->create(CalendarEvent::fromArray($event))->toArray();
    }

    public function findEvent(string $eventId): ?array
    {
        return $this->memory->get($eventId)?->toArray();
    }

    public function removeEvent(string $eventId): void
    {
        $this->memory->delete($eventId);
    }

    public function allEvents(?string $from = null, ?string $to = null): array
    {
        return array_map(static fn (CalendarEvent $e): array => $e->toArray(), $this->memory->list($from, $to));
    }

    public function toIcs(): string
    {
        $lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//SERIXA//iCal//EN'];
        foreach ($this->memory->list() as $event) {
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . $event->id;
            $lines[] = 'SUMMARY:' . $event->title;
            $lines[] = 'DTSTART:' . $event->start;
            $lines[] = 'DTEND:' . $event->end;
            $lines[] = 'END:VEVENT';
        }
        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines);
    }
}
