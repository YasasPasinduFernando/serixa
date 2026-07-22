<?php

declare(strict_types=1);

namespace Serixa\Audit\Memory;

use Serixa\Audit\AuditEvent;
use Serixa\Audit\Contracts\AuditStore;

final class MemoryAuditStore implements AuditStore
{
    /** @var list<AuditEvent> */
    private array $events = [];

    public function append(AuditEvent $event): void
    {
        $this->events[] = $event;
    }

    public function get(string $id): ?AuditEvent
    {
        foreach ($this->events as $event) {
            if ($event->id() === $id) {
                return $event;
            }
        }

        return null;
    }

    public function latest(int $limit = 100): array
    {
        // Newest first: reverse append order (stable for same-second timestamps).
        $sorted = array_reverse($this->events);

        return array_slice($sorted, 0, max(0, $limit));
    }

    public function forActor(string $actorId, int $limit = 100): array
    {
        $out = [];
        foreach ($this->latest(PHP_INT_MAX) as $event) {
            if ($event->actorId() === $actorId) {
                $out[] = $event;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    public function forTarget(string $targetType, string $targetId, int $limit = 100): array
    {
        $out = [];
        foreach ($this->latest(PHP_INT_MAX) as $event) {
            if ($event->targetType() === $targetType && $event->targetId() === $targetId) {
                $out[] = $event;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    public function count(): int
    {
        return count($this->events);
    }
}
