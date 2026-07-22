<?php

declare(strict_types=1);

namespace Serixa\Audit\Contracts;

use Serixa\Audit\AuditEvent;

/**
 * Append-only audit store — no update or delete in the contract.
 */
interface AuditStore
{
    public function append(AuditEvent $event): void;

    public function get(string $id): ?AuditEvent;

    /**
     * @return list<AuditEvent>
     */
    public function latest(int $limit = 100): array;

    /**
     * @return list<AuditEvent>
     */
    public function forActor(string $actorId, int $limit = 100): array;

    /**
     * @return list<AuditEvent>
     */
    public function forTarget(string $targetType, string $targetId, int $limit = 100): array;

    public function count(): int;
}
