<?php

declare(strict_types=1);

namespace Serixa\Teams;

use Serixa\Exception\InvalidArgumentException;

final class TeamMembership
{
    public function __construct(
        private readonly string $teamId,
        private readonly string $memberId,
        private readonly string $role = 'member',
    ) {
        if ($teamId === '' || $memberId === '') {
            throw new InvalidArgumentException('Team membership requires team and member ids.');
        }
    }

    public static function make(string $teamId, string $memberId, string $role = 'member'): self
    {
        return new self($teamId, $memberId, $role);
    }

    public function teamId(): string
    {
        return $this->teamId;
    }

    public function memberId(): string
    {
        return $this->memberId;
    }

    public function role(): string
    {
        return $this->role;
    }
}
