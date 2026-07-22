<?php

declare(strict_types=1);

namespace Serixa\Teams;

use Serixa\Exception\InvalidArgumentException;

final class Team
{
    public function __construct(
        private readonly string $id,
        private readonly string $organizationId,
        private readonly string $name,
    ) {
        if ($id === '' || $organizationId === '' || $name === '') {
            throw new InvalidArgumentException('Team id, organizationId, and name are required.');
        }
    }

    public static function make(string $id, string $organizationId, string $name): self
    {
        return new self($id, $organizationId, $name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function organizationId(): string
    {
        return $this->organizationId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
