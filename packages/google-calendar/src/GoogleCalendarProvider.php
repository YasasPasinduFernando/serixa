<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar;

use Serixa\GoogleCalendar\Contracts\CalendarProvider;
use Serixa\GoogleCalendar\Contracts\GoogleCalendarClient;

final class GoogleCalendarProvider implements CalendarProvider
{
    public function __construct(
        private readonly GoogleCalendarClient $client,
        private readonly string $calendarId = 'primary',
    ) {
    }

    public static function make(GoogleCalendarClient $client, string $calendarId = 'primary'): self
    {
        return new self($client, $calendarId);
    }

    public function create(CalendarEvent $event): CalendarEvent
    {
        return CalendarEvent::fromArray($this->client->insert($this->calendarId, $event->toArray()));
    }

    public function get(string $eventId): ?CalendarEvent
    {
        $data = $this->client->get($this->calendarId, $eventId);

        return $data === null ? null : CalendarEvent::fromArray($data);
    }

    public function delete(string $eventId): void
    {
        $this->client->delete($this->calendarId, $eventId);
    }

    public function list(?string $from = null, ?string $to = null): array
    {
        return array_map(
            static fn (array $row): CalendarEvent => CalendarEvent::fromArray($row),
            $this->client->list($this->calendarId, $from, $to),
        );
    }
}
