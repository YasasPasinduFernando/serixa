<?php

declare(strict_types=1);

namespace Serixa\Caldav;

use Serixa\Caldav\Contracts\CaldavClient;
use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\Contracts\CalendarProvider;

final class CaldavCalendarProvider implements CalendarProvider
{
    public function __construct(
        private readonly CaldavClient $client,
        private readonly string $calendarHref = '/calendars/default/',
    ) {
    }

    public static function make(CaldavClient $client, string $calendarHref = '/calendars/default/'): self
    {
        return new self($client, $calendarHref);
    }

    public function create(CalendarEvent $event): CalendarEvent
    {
        return CalendarEvent::fromArray($this->client->putEvent($this->calendarHref, $event->toArray()));
    }

    public function get(string $eventId): ?CalendarEvent
    {
        $data = $this->client->getEvent($this->calendarHref, $eventId);

        return $data === null ? null : CalendarEvent::fromArray($data);
    }

    public function delete(string $eventId): void
    {
        $this->client->deleteEvent($this->calendarHref, $eventId);
    }

    public function list(?string $from = null, ?string $to = null): array
    {
        return array_map(
            static fn (array $row): CalendarEvent => CalendarEvent::fromArray($row),
            $this->client->report($this->calendarHref, $from, $to),
        );
    }
}
