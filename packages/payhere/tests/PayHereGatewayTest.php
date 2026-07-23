<?php

declare(strict_types=1);

namespace Serixa\Payhere\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Payhere\FakePayHereClient;
use Serixa\Payhere\PayHereGateway;

final class PayHereGatewayTest extends TestCase
{
    public function testCharge(): void
    {
        $result = PayHereGateway::make(new FakePayHereClient())->charge(500, 'LKR', 'ord-1');
        self::assertTrue($result->success);
        self::assertSame('payhere', $result->provider);
    }
}
