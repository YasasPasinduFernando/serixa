<?php

declare(strict_types=1);

namespace Serixa\Checkout;

use Serixa\Cart\Money;

final class OrderItem
{
    public function __construct(
        private readonly string $sku,
        private readonly string $name,
        private readonly Money $unitPrice,
        private readonly int $quantity,
    ) {
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function lineTotal(): Money
    {
        return $this->unitPrice->multiply($this->quantity);
    }
}
