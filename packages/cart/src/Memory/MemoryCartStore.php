<?php

declare(strict_types=1);

namespace Serixa\Cart\Memory;

use Serixa\Cart\Cart;
use Serixa\Cart\Contracts\CartStore;

final class MemoryCartStore implements CartStore
{
    /** @var array<string, Cart> */
    private array $carts = [];

    public function put(Cart $cart): void
    {
        $this->carts[$cart->id()] = $cart;
    }

    public function get(string $id): ?Cart
    {
        return $this->carts[$id] ?? null;
    }

    public function remove(string $id): void
    {
        unset($this->carts[$id]);
    }
}
