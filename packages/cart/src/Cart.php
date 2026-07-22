<?php

declare(strict_types=1);

namespace Serixa\Cart;

use Serixa\Cart\Exceptions\CartException;

final class Cart
{
    /** @var array<string, CartItem> */
    private array $items = [];

    public function __construct(
        private readonly string $id,
        private readonly string $currency = 'USD',
    ) {
    }

    public static function make(string $id, string $currency = 'USD'): self
    {
        return new self($id, strtoupper($currency));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(CartItem $item): self
    {
        if ($item->unitPrice()->currency() !== $this->currency) {
            throw CartException::currencyMismatch($this->currency, $item->unitPrice()->currency());
        }

        if (isset($this->items[$item->sku()])) {
            $existing = $this->items[$item->sku()];
            $this->items[$item->sku()] = $existing->withQuantity($existing->quantity() + $item->quantity());
        } else {
            $this->items[$item->sku()] = $item;
        }

        return $this;
    }

    public function updateQuantity(string $sku, int $quantity): self
    {
        if (! isset($this->items[$sku])) {
            throw CartException::itemNotFound($sku);
        }
        if ($quantity < 1) {
            unset($this->items[$sku]);

            return $this;
        }
        $this->items[$sku] = $this->items[$sku]->withQuantity($quantity);

        return $this;
    }

    public function remove(string $sku): self
    {
        unset($this->items[$sku]);

        return $this;
    }

    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    public function has(string $sku): bool
    {
        return isset($this->items[$sku]);
    }

    public function get(string $sku): ?CartItem
    {
        return $this->items[$sku] ?? null;
    }

    /**
     * @return list<CartItem>
     */
    public function items(): array
    {
        return array_values($this->items);
    }

    public function count(): int
    {
        $n = 0;
        foreach ($this->items as $item) {
            $n += $item->quantity();
        }

        return $n;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function subtotal(): Money
    {
        $total = Money::of(0, $this->currency);
        foreach ($this->items as $item) {
            $total = $total->add($item->lineTotal());
        }

        return $total;
    }
}
