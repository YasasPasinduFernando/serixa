<?php

declare(strict_types=1);

namespace Serixa\Rbac\Exceptions;

use Serixa\Exception\SerixaException;

final class AuthorizationException extends SerixaException
{
    public static function denied(string $ability, string $subjectId): self
    {
        return new self(sprintf(
            'Authorization denied for subject [%s] on ability [%s].',
            $subjectId,
            $ability,
        ));
    }
}
