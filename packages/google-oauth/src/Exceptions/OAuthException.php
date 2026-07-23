<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth\Exceptions;

use RuntimeException;

final class OAuthException extends RuntimeException
{
    public static function emptyCode(): self
    {
        return new self('Authorization code must not be empty.');
    }

    public static function emptyToken(): self
    {
        return new self('Access token must not be empty.');
    }
}
