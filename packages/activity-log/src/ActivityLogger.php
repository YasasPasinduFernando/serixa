<?php

declare(strict_types=1);

namespace Serixa\ActivityLog;

use Serixa\ActivityLog\Contracts\ActivityStore;
use Serixa\ActivityLog\Memory\MemoryActivityStore;

final class ActivityLogger
{
    private int $seq = 0;

    public function __construct(private readonly ActivityStore $store)
    {
    }

    public static function memory(): self
    {
        return new self(new MemoryActivityStore());
    }

    public function store(): ActivityStore
    {
        return $this->store;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function log(
        string $description,
        ?string $actorId = null,
        ?string $subjectType = null,
        ?string $subjectId = null,
        array $properties = [],
        ?string $id = null,
    ): ActivityEntry {
        $entry = ActivityEntry::make(
            $id ?? $this->nextId(),
            $description,
            $actorId,
            $subjectType,
            $subjectId,
            $properties,
        );
        $this->store->append($entry);

        return $entry;
    }

    private function nextId(): string
    {
        ++$this->seq;

        return 'act_' . $this->seq . '_' . bin2hex(random_bytes(4));
    }
}
