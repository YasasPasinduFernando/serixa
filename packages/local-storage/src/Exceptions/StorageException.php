<?php

declare(strict_types=1);

namespace Serixa\LocalStorage\Exceptions;

use Serixa\Exception\SerixaException;

final class StorageException extends SerixaException
{
    public static function notFound(string $path): self
    {
        return new self(sprintf('Storage object [%s] was not found.', $path));
    }

    public static function writeFailed(string $path): self
    {
        return new self(sprintf('Failed to write storage object [%s].', $path));
    }

    public static function invalidPath(string $path): self
    {
        return new self(sprintf('Invalid storage path [%s].', $path));
    }
}
