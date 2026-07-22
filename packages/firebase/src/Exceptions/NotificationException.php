<?php

declare(strict_types=1);

namespace Serixa\Firebase\Exceptions;

use RuntimeException;

final class NotificationException extends RuntimeException
{
    public static function emptyRecipient(): self
    {
        return new self('Notification recipient must not be empty.');
    }

    public static function emptyBody(): self
    {
        return new self('Notification body must not be empty.');
    }
}
