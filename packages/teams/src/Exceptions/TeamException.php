<?php

declare(strict_types=1);

namespace Serixa\Teams\Exceptions;

use Serixa\Exception\SerixaException;

final class TeamException extends SerixaException
{
    public static function organizationRequired(string $organizationId): self
    {
        return new self(sprintf('Organization [%s] does not exist; cannot create team.', $organizationId));
    }

    public static function notFound(string $teamId): self
    {
        return new self(sprintf('Team [%s] was not found.', $teamId));
    }
}
