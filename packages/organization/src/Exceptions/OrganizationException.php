<?php

declare(strict_types=1);

namespace Serixa\Organization\Exceptions;

use Serixa\Exception\SerixaException;

final class OrganizationException extends SerixaException
{
    public static function notFound(string $id): self
    {
        return new self(sprintf('Organization [%s] was not found.', $id));
    }

    public static function noContext(): self
    {
        return new self('No current organization is set in OrgContext.');
    }
}
