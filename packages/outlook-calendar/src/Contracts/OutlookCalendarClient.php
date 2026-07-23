<?php

declare(strict_types=1);

namespace Serixa\OutlookCalendar\Contracts;

interface OutlookCalendarClient
{
    /**
     * @param array{id?: string, title: string, start: string, end: string, description?: string} $event
     * @return array{id: string, title: string, start: string, end: string, description?: string}
     */
    public function createEvent(array $event): array;

    /** @return array{id: string, title: string, start: string, end: string, description?: string}|null */
    public function getEvent(string $eventId): ?array;

    public function deleteEvent(string $eventId): void;

    /**
     * @return list<array{id: string, title: string, start: string, end: string, description?: string}>
     */
    public function listEvents(?string $from = null, ?string $to = null): array;
}
