<?php

declare(strict_types=1);

namespace Serixa\Ical;

use Serixa\GoogleCalendar\CalendarEvent;
use Serixa\GoogleCalendar\Contracts\CalendarProvider;
use Serixa\Ical\Contracts\IcalClient;

final class IcalCalendarProvider implements CalendarProvider
{
    public function __construct(private readonly IcalClient $client)
    {
    }

    public static function make(IcalClient $client): self
    {
        return new self($client);
    }

    public function create(CalendarEvent $event): CalendarEvent
    {
        return CalendarEvent::fromArray($this->client->addEvent($event->toArray()));
    }

    public function get(string $eventId): ?CalendarEvent
    {
        $data = $this->client->findEvent($eventId);

        return $data === null ? null : CalendarEvent::fromArray($data);
    }

    public function delete(string $eventId): void
    {
        $this->client->removeEvent($eventId);
    }

    public function list(?string $from = null, ?string $to = null): array
    {
        return array_map(
            static fn (array $row): CalendarEvent => CalendarEvent::fromArray($row),
            $this->client->allEvents($from, $to),
        );
    }

    public function export(): string
    {
        return $this->client->toIcs();
    }
}
