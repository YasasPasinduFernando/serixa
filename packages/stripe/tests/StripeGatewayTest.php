<?php

declare(strict_types=1);

namespace Serixa\Stripe\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Cart\Money;
use Serixa\Checkout\Address;
use Serixa\Checkout\Order;
use Serixa\Stripe\FakeStripeClient;
use Serixa\Stripe\StripeGateway;

final class StripeGatewayTest extends TestCase
{
    public function testCharge(): void
    {
        $result = StripeGateway::make(new FakeStripeClient())->charge(2500, 'USD', 'ord-1');
        self::assertTrue($result->success);
        self::assertSame('stripe', $result->provider);
    }

    public function testChargeOrder(): void
    {
        $order = new Order('ord-2', [], Money::of(999, 'USD'), new Address('A', 'B', 'C', 'D', 'E', 'US'));
        $result = StripeGateway::make(new FakeStripeClient())->chargeOrder($order);
        self::assertTrue($result->success);
        self::assertStringStartsWith('pi_', $result->id);
    }
}
