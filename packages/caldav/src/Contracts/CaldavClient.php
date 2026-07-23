<?php

declare(strict_types=1);

namespace Serixa\Caldav\Contracts;

interface CaldavClient
{
    /**
     * @param array{id?: string, title: string, start: string, end: string, description?: string} $event
     * @return array{id: string, title: string, start: string, end: string, description?: string}
     */
    public function putEvent(string $calendarHref, array $event): array;

    /** @return array{id: string, title: string, start: string, end: string, description?: string}|null */
    public function getEvent(string $calendarHref, string $eventId): ?array;

    public function deleteEvent(string $calendarHref, string $eventId): void;

    /**
     * @return list<array{id: string, title: string, start: string, end: string, description?: string}>
     */
    public function report(string $calendarHref, ?string $from = null, ?string $to = null): array;
}
