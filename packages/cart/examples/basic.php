<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Cart\Cart;
use Serixa\Cart\CartItem;
use Serixa\Cart\Money;

$cart = Cart::make('demo');
$cart->add(CartItem::make('book', 'SERIXA Book', Money::of(2500), 2));
$cart->add(CartItem::make('sticker', 'Sticker Pack', Money::of(500)));

echo 'items: ', count($cart->items()), PHP_EOL;
echo 'qty: ', $cart->count(), PHP_EOL;
echo 'subtotal: ', $cart->subtotal()->amount(), ' ', $cart->currency(), PHP_EOL;

if ($cart->count() === 3 && $cart->subtotal()->amount() === 5500) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
