<?php

declare(strict_types=1);

namespace Serixa\ActivityLog\Memory;

use Serixa\ActivityLog\ActivityEntry;
use Serixa\ActivityLog\Contracts\ActivityStore;

final class MemoryActivityStore implements ActivityStore
{
    /** @var list<ActivityEntry> */
    private array $entries = [];

    public function append(ActivityEntry $entry): void
    {
        $this->entries[] = $entry;
    }

    public function get(string $id): ?ActivityEntry
    {
        foreach ($this->entries as $entry) {
            if ($entry->id() === $id) {
                return $entry;
            }
        }

        return null;
    }

    public function latest(int $limit = 50): array
    {
        // Newest first: reverse append order (stable for same-second timestamps).
        $sorted = array_reverse($this->entries);

        return array_slice($sorted, 0, max(0, $limit));
    }

    public function forSubject(string $subjectType, string $subjectId, int $limit = 50): array
    {
        $out = [];
        foreach ($this->latest(PHP_INT_MAX) as $entry) {
            if ($entry->subjectType() === $subjectType && $entry->subjectId() === $subjectId) {
                $out[] = $entry;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    public function forActor(string $actorId, int $limit = 50): array
    {
        $out = [];
        foreach ($this->latest(PHP_INT_MAX) as $entry) {
            if ($entry->actorId() === $actorId) {
                $out[] = $entry;
            }
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    public function count(): int
    {
        return count($this->entries);
    }
}
