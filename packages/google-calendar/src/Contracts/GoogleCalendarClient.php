<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar\Contracts;

interface GoogleCalendarClient
{
    /**
     * @param array{id?: string, title: string, start: string, end: string, description?: string} $event
     * @return array{id: string, title: string, start: string, end: string, description?: string}
     */
    public function insert(string $calendarId, array $event): array;

    /**
     * @return array{id: string, title: string, start: string, end: string, description?: string}|null
     */
    public function get(string $calendarId, string $eventId): ?array;

    public function delete(string $calendarId, string $eventId): void;

    /**
     * @return list<array{id: string, title: string, start: string, end: string, description?: string}>
     */
    public function list(string $calendarId, ?string $from = null, ?string $to = null): array;
}
