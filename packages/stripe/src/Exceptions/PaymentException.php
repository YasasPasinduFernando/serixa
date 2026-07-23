<?php

declare(strict_types=1);

namespace Serixa\Stripe\Exceptions;

use RuntimeException;

final class PaymentException extends RuntimeException
{
    public static function invalidAmount(int $amount): self
    {
        return new self(sprintf('Payment amount must be positive, got %d.', $amount));
    }

    public static function emptyReference(): self
    {
        return new self('Payment reference must not be empty.');
    }
}
