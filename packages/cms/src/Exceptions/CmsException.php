<?php

declare(strict_types=1);

namespace Serixa\Cms\Exceptions;

use Serixa\Exception\SerixaException;

final class CmsException extends SerixaException
{
    public static function notFound(string $id): self
    {
        return new self(sprintf('CMS page [%s] was not found.', $id));
    }

    public static function invalidTransition(string $from, string $to): self
    {
        return new self(sprintf('Cannot transition CMS page from [%s] to [%s].', $from, $to));
    }
}
