<?php

declare(strict_types=1);

namespace Serixa\Paypal\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Paypal\FakePayPalClient;
use Serixa\Paypal\PayPalGateway;

final class PayPalGatewayTest extends TestCase
{
    public function testCharge(): void
    {
        $result = PayPalGateway::make(new FakePayPalClient())->charge(1000, 'USD', 'ord-1');
        self::assertTrue($result->success);
        self::assertSame('paypal', $result->provider);
    }
}
