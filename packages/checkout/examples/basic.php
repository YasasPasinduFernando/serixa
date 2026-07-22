<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Cart\Cart;
use Serixa\Cart\CartItem;
use Serixa\Cart\Money;
use Serixa\Checkout\Address;
use Serixa\Checkout\Checkout;

$cart = Cart::make('demo');
$cart->add(CartItem::make('mug', 'Mug', Money::of(1200)));
$order = Checkout::for($cart)
    ->shipTo(Address::make('Yasas', '42 Git St', 'Kandy', 'LK', null, '20000'))
    ->place();

echo 'order: ', $order->id(), PHP_EOL;
echo 'total: ', $order->subtotal()->amount(), PHP_EOL;
echo 'cart empty: ', $cart->isEmpty() ? 'yes' : 'no', PHP_EOL;

if ($order->subtotal()->amount() === 1200 && $cart->isEmpty()) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
