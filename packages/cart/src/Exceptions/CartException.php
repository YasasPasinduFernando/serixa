<?php

declare(strict_types=1);

namespace Serixa\Cart\Exceptions;

use Serixa\Exception\SerixaException;

final class CartException extends SerixaException
{
    public static function itemNotFound(string $sku): self
    {
        return new self(sprintf('Cart item [%s] was not found.', $sku));
    }

    public static function currencyMismatch(string $cart, string $item): self
    {
        return new self(sprintf('Cart currency [%s] does not match item currency [%s].', $cart, $item));
    }
}
