<?php

declare(strict_types=1);

namespace Serixa\Ical\Contracts;

interface IcalClient
{
    /**
     * @param array{id?: string, title: string, start: string, end: string, description?: string} $event
     * @return array{id: string, title: string, start: string, end: string, description?: string}
     */
    public function addEvent(array $event): array;

    /** @return array{id: string, title: string, start: string, end: string, description?: string}|null */
    public function findEvent(string $eventId): ?array;

    public function removeEvent(string $eventId): void;

    /**
     * @return list<array{id: string, title: string, start: string, end: string, description?: string}>
     */
    public function allEvents(?string $from = null, ?string $to = null): array;

    public function toIcs(): string;
}
