<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar\Contracts;

use Serixa\GoogleCalendar\CalendarEvent;

interface CalendarProvider
{
    public function create(CalendarEvent $event): CalendarEvent;

    public function get(string $eventId): ?CalendarEvent;

    public function delete(string $eventId): void;

    /**
     * @return list<CalendarEvent>
     */
    public function list(?string $from = null, ?string $to = null): array;
}
