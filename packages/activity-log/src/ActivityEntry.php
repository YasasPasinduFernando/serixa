<?php

declare(strict_types=1);

namespace Serixa\ActivityLog;

use DateTimeImmutable;

/**
 * Domain activity entry for feeds and history.
 *
 * Distinct from framework UI widgets ActivityList / ActivityItem.
 */
final class ActivityEntry
{
    /**
     * @param array<string, mixed> $properties
     */
    public function __construct(
        private readonly string $id,
        private readonly string $description,
        private readonly ?string $actorId = null,
        private readonly ?string $subjectType = null,
        private readonly ?string $subjectId = null,
        private readonly array $properties = [],
        private readonly ?DateTimeImmutable $occurredAt = null,
    ) {
    }

    /**
     * @param array<string, mixed> $properties
     */
    public static function make(
        string $id,
        string $description,
        ?string $actorId = null,
        ?string $subjectType = null,
        ?string $subjectId = null,
        array $properties = [],
        ?DateTimeImmutable $occurredAt = null,
    ): self {
        return new self(
            $id,
            $description,
            $actorId,
            $subjectType,
            $subjectId,
            $properties,
            $occurredAt,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function actorId(): ?string
    {
        return $this->actorId;
    }

    public function subjectType(): ?string
    {
        return $this->subjectType;
    }

    public function subjectId(): ?string
    {
        return $this->subjectId;
    }

    /**
     * @return array<string, mixed>
     */
    public function properties(): array
    {
        return $this->properties;
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt ?? new DateTimeImmutable();
    }

    /**
     * @return array{id: string, description: string, actor_id: ?string, subject_type: ?string, subject_id: ?string, properties: array<string, mixed>, occurred_at: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'actor_id' => $this->actorId,
            'subject_type' => $this->subjectType,
            'subject_id' => $this->subjectId,
            'properties' => $this->properties,
            'occurred_at' => $this->occurredAt->format(DATE_ATOM),
        ];
    }
}
