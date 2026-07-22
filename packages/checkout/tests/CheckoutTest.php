<?php

declare(strict_types=1);

namespace Serixa\Checkout\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cart\Cart;
use Serixa\Cart\CartItem;
use Serixa\Cart\Money;
use Serixa\Checkout\Address;
use Serixa\Checkout\Checkout;
use Serixa\Checkout\Exceptions\CheckoutException;

final class CheckoutTest extends TestCase
{
    public function testPlaceOrder(): void
    {
        $cart = Cart::make('c1');
        $cart->add(CartItem::make('a', 'A', Money::of(1000), 2));
        $order = Checkout::for($cart)
            ->shipTo(Address::make('Ada', '1 Main', 'Colombo', 'LK'))
            ->place('ord-1');

        self::assertSame('ord-1', $order->id());
        self::assertSame(2000, $order->subtotal()->amount());
        self::assertTrue($cart->isEmpty());
    }

    public function testEmptyCartThrows(): void
    {
        $this->expectException(CheckoutException::class);
        Checkout::for(Cart::make('empty'))
            ->shipTo(Address::make('Ada', '1 Main', 'Colombo', 'LK'))
            ->place();
    }
}
