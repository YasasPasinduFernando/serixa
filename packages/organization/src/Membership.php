<?php

declare(strict_types=1);

namespace Serixa\Organization;

use Serixa\Exception\InvalidArgumentException;

final class Membership
{
    public function __construct(
        private readonly string $organizationId,
        private readonly string $memberId,
        private readonly string $role = 'member',
    ) {
        if ($organizationId === '' || $memberId === '') {
            throw new InvalidArgumentException('Membership requires organization and member ids.');
        }
    }

    public static function make(string $organizationId, string $memberId, string $role = 'member'): self
    {
        return new self($organizationId, $memberId, $role);
    }

    public function organizationId(): string
    {
        return $this->organizationId;
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
