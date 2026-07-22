<?php

declare(strict_types=1);

namespace Serixa\Openai\Exceptions;

use RuntimeException;

final class AiException extends RuntimeException
{
    public static function emptyMessages(): self
    {
        return new self('Chat requires at least one message.');
    }

    public static function invalidRole(string $role): self
    {
        return new self(sprintf('Invalid chat role: "%s".', $role));
    }

    public static function emptyPrompt(): self
    {
        return new self('Completion prompt must not be empty.');
    }

    public static function emptyModel(): self
    {
        return new self('Model name must not be empty.');
    }
}
