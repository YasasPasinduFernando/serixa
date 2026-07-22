<?php

declare(strict_types=1);

namespace Serixa\MediaLibrary\Exceptions;

use Serixa\Exception\SerixaException;

final class MediaException extends SerixaException
{
    public static function notFound(string $id): self
    {
        return new self(sprintf('Media item [%s] was not found.', $id));
    }
}
