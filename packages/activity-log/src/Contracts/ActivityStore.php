<?php

declare(strict_types=1);

namespace Serixa\ActivityLog\Contracts;

use Serixa\ActivityLog\ActivityEntry;

interface ActivityStore
{
    public function append(ActivityEntry $entry): void;

    public function get(string $id): ?ActivityEntry;

    /**
     * Newest first.
     *
     * @return list<ActivityEntry>
     */
    public function latest(int $limit = 50): array;

    /**
     * @return list<ActivityEntry>
     */
    public function forSubject(string $subjectType, string $subjectId, int $limit = 50): array;

    /**
     * @return list<ActivityEntry>
     */
    public function forActor(string $actorId, int $limit = 50): array;

    public function count(): int;
}
