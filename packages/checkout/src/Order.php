<?php

declare(strict_types=1);

namespace Serixa\Checkout;

use DateTimeImmutable;
use Serixa\Cart\Money;

final class Order
{
    /**
     * @param list<OrderItem> $items
     */
    public function __construct(
        private readonly string $id,
        private readonly array $items,
        private readonly Money $subtotal,
        private readonly Address $shippingAddress,
        private readonly string $status = 'placed',
        private readonly ?DateTimeImmutable $placedAt = null,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return list<OrderItem>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function subtotal(): Money
    {
        return $this->subtotal;
    }

    public function shippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function placedAt(): DateTimeImmutable
    {
        return $this->placedAt ?? new DateTimeImmutable();
    }
}
