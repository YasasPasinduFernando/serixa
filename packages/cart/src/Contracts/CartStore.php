<?php

declare(strict_types=1);

namespace Serixa\Cart\Contracts;

use Serixa\Cart\Cart;

interface CartStore
{
    public function put(Cart $cart): void;

    public function get(string $id): ?Cart;

    public function remove(string $id): void;
}
