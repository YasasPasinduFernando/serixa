<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar;

use Serixa\GoogleCalendar\Contracts\CalendarProvider;

final class MemoryCalendar implements CalendarProvider
{
    /** @var array<string, CalendarEvent> */
    private array $events = [];

    private int $seq = 0;

    public function create(CalendarEvent $event): CalendarEvent
    {
        ++$this->seq;
        $stored = $event->withId('evt-' . $this->seq);
        $this->events[$stored->id] = $stored;

        return $stored;
    }

    public function get(string $eventId): ?CalendarEvent
    {
        return $this->events[$eventId] ?? null;
    }

    public function delete(string $eventId): void
    {
        unset($this->events[$eventId]);
    }

    public function list(?string $from = null, ?string $to = null): array
    {
        $out = [];
        foreach ($this->events as $event) {
            if ($from !== null && $event->end < $from) {
                continue;
            }
            if ($to !== null && $event->start > $to) {
                continue;
            }
            $out[] = $event;
        }

        return $out;
    }
}
