<?php

declare(strict_types=1);

namespace Serixa\Checkout\Exceptions;

use Serixa\Exception\SerixaException;

final class CheckoutException extends SerixaException
{
    public static function emptyCart(): self
    {
        return new self('Cannot checkout an empty cart.');
    }

    public static function missingAddress(): self
    {
        return new self('Shipping address is required to place an order.');
    }
}
