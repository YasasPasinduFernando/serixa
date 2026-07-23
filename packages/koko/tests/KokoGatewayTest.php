<?php

declare(strict_types=1);

namespace Serixa\Koko\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Koko\FakeKokoClient;
use Serixa\Koko\KokoGateway;

final class KokoGatewayTest extends TestCase
{
    public function testCharge(): void
    {
        $result = KokoGateway::make(new FakeKokoClient())->charge(750, 'LKR', 'ord-1');
        self::assertTrue($result->success);
        self::assertSame('koko', $result->provider);
    }
}
