<?php

declare(strict_types=1);

namespace Serixa\Audit;

use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;

/**
 * Immutable compliance audit event.
 */
final class AuditEvent
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        private readonly string $id,
        private readonly string $action,
        private readonly ?string $actorId = null,
        private readonly ?string $targetType = null,
        private readonly ?string $targetId = null,
        private readonly array $metadata = [],
        private readonly ?DateTimeImmutable $recordedAt = null,
    ) {
        if ($id === '' || $action === '') {
            throw new InvalidArgumentException('Audit event id and action are required.');
        }
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public static function make(
        string $id,
        string $action,
        ?string $actorId = null,
        ?string $targetType = null,
        ?string $targetId = null,
        array $metadata = [],
        ?DateTimeImmutable $recordedAt = null,
    ): self {
        return new self($id, $action, $actorId, $targetType, $targetId, $metadata, $recordedAt);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function actorId(): ?string
    {
        return $this->actorId;
    }

    public function targetType(): ?string
    {
        return $this->targetType;
    }

    public function targetId(): ?string
    {
        return $this->targetId;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    public function recordedAt(): DateTimeImmutable
    {
        return $this->recordedAt ?? new DateTimeImmutable();
    }
}
