<?php

declare(strict_types=1);

namespace Serixa\Zip\Exceptions;

use Serixa\Exception\SerixaException;

final class ZipException extends SerixaException
{
    public static function extensionMissing(): self
    {
        return new self('PHP zip extension (ZipArchive) is required.');
    }

    public static function openFailed(string $path): self
    {
        return new self(sprintf('Unable to open zip archive [%s].', $path));
    }
}
