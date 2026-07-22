<?php

declare(strict_types=1);

namespace Serixa\Audit;

use Serixa\ActivityLog\ActivityLogger;
use Serixa\Audit\Contracts\AuditStore;
use Serixa\Audit\Memory\MemoryAuditStore;

/**
 * Records immutable audit events; optionally mirrors into activity-log.
 */
final class AuditLogger
{
    private int $seq = 0;

    public function __construct(
        private readonly AuditStore $store,
        private readonly ?ActivityLogger $activity = null,
    ) {
    }

    public static function memory(?ActivityLogger $activity = null): self
    {
        return new self(new MemoryAuditStore(), $activity);
    }

    public function store(): AuditStore
    {
        return $this->store;
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function record(
        string $action,
        ?string $actorId = null,
        ?string $targetType = null,
        ?string $targetId = null,
        array $metadata = [],
        ?string $id = null,
    ): AuditEvent {
        $event = AuditEvent::make(
            $id ?? $this->nextId(),
            $action,
            $actorId,
            $targetType,
            $targetId,
            $metadata,
        );
        $this->store->append($event);

        if ($this->activity !== null) {
            $this->activity->log(
                'audit:' . $action,
                $actorId,
                $targetType,
                $targetId,
                $metadata,
                'activity_for_' . $event->id(),
            );
        }

        return $event;
    }

    private function nextId(): string
    {
        ++$this->seq;

        return 'aud_' . $this->seq . '_' . bin2hex(random_bytes(4));
    }
}
