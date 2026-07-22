<?php

declare(strict_types=1);

namespace Serixa\Checkout;

use Serixa\Cart\Cart;
use Serixa\Checkout\Exceptions\CheckoutException;

/**
 * Turns a Cart into an Order.
 */
final class Checkout
{
    private ?Address $shipping = null;

    private int $seq = 0;

    public function __construct(private readonly Cart $cart)
    {
    }

    public static function for(Cart $cart): self
    {
        return new self($cart);
    }

    public function shipTo(Address $address): self
    {
        $this->shipping = $address;

        return $this;
    }

    public function place(?string $orderId = null): Order
    {
        if ($this->cart->isEmpty()) {
            throw CheckoutException::emptyCart();
        }
        if ($this->shipping === null) {
            throw CheckoutException::missingAddress();
        }

        $items = [];
        foreach ($this->cart->items() as $item) {
            $items[] = new OrderItem(
                $item->sku(),
                $item->name(),
                $item->unitPrice(),
                $item->quantity(),
            );
        }

        $order = new Order(
            $orderId ?? $this->nextId(),
            $items,
            $this->cart->subtotal(),
            $this->shipping,
        );

        $this->cart->clear();

        return $order;
    }

    private function nextId(): string
    {
        ++$this->seq;

        return 'ord_' . $this->seq . '_' . bin2hex(random_bytes(3));
    }
}
