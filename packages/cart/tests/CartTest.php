<?php

declare(strict_types=1);

namespace Serixa\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cart\Cart;
use Serixa\Cart\CartItem;
use Serixa\Cart\Exceptions\CartException;
use Serixa\Cart\Money;

final class CartTest extends TestCase
{
    public function testAddAndSubtotal(): void
    {
        $cart = Cart::make('c1');
        $cart->add(CartItem::make('sku-a', 'Widget', Money::of(1000), 2));
        $cart->add(CartItem::make('sku-a', 'Widget', Money::of(1000), 1));

        self::assertSame(3, $cart->count());
        self::assertSame(3000, $cart->subtotal()->amount());
    }

    public function testRemoveAndClear(): void
    {
        $cart = Cart::make('c2');
        $cart->add(CartItem::make('x', 'X', Money::of(500)));
        $cart->remove('x');
        self::assertTrue($cart->isEmpty());
    }

    public function testCurrencyMismatch(): void
    {
        $cart = Cart::make('c3', 'USD');
        $this->expectException(CartException::class);
        $cart->add(CartItem::make('e', 'Euro', Money::of(100, 'EUR')));
    }
}
