<?php

declare(strict_types=1);

namespace Serixa\OutlookCalendar;

use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\Contracts\CalendarProvider;
use Serixa\OutlookCalendar\Contracts\OutlookCalendarClient;

final class OutlookCalendarProvider implements CalendarProvider
{
    public function __construct(private readonly OutlookCalendarClient $client)
    {
    }

    public static function make(OutlookCalendarClient $client): self
    {
        return new self($client);
    }

    public function create(CalendarEvent $event): CalendarEvent
    {
        return CalendarEvent::fromArray($this->client->createEvent($event->toArray()));
    }

    public function get(string $eventId): ?CalendarEvent
    {
        $data = $this->client->getEvent($eventId);

        return $data === null ? null : CalendarEvent::fromArray($data);
    }

    public function delete(string $eventId): void
    {
        $this->client->deleteEvent($eventId);
    }

    public function list(?string $from = null, ?string $to = null): array
    {
        return array_map(
            static fn (array $row): CalendarEvent => CalendarEvent::fromArray($row),
            $this->client->listEvents($from, $to),
        );
    }
}
