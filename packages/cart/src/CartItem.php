<?php

declare(strict_types=1);

namespace Serixa\Cart;

use Serixa\Exception\InvalidArgumentException;

final class CartItem
{
    public function __construct(
        private readonly string $sku,
        private readonly string $name,
        private readonly Money $unitPrice,
        private readonly int $quantity = 1,
    ) {
        if ($sku === '' || $name === '') {
            throw new InvalidArgumentException('Cart item sku and name are required.');
        }
        if ($quantity < 1) {
            throw new InvalidArgumentException('Cart item quantity must be at least 1.');
        }
    }

    public static function make(string $sku, string $name, Money $unitPrice, int $quantity = 1): self
    {
        return new self($sku, $name, $unitPrice, $quantity);
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

    public function withQuantity(int $quantity): self
    {
        return new self($this->sku, $this->name, $this->unitPrice, $quantity);
    }
}
