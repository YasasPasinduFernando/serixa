<?php

declare(strict_types=1);

namespace Serixa\Passkeys\Exceptions;

use Serixa\Exception\SerixaException;

final class PasskeyException extends SerixaException
{
    public static function challengeNotFound(string $id): self
    {
        return new self(sprintf('Passkey challenge [%s] was not found.', $id));
    }

    public static function challengeExpired(string $id): self
    {
        return new self(sprintf('Passkey challenge [%s] has expired.', $id));
    }

    public static function credentialNotFound(string $id): self
    {
        return new self(sprintf('Passkey credential [%s] was not found.', $id));
    }
}
