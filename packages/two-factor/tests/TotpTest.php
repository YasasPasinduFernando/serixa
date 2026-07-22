<?php

declare(strict_types=1);

namespace Serixa\TwoFactor\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\TwoFactor\Totp;

final class TotpTest extends TestCase
{
    public function testGenerateAndVerify(): void
    {
        $totp = Totp::make(Totp::generateSecret());
        $code = $totp->now();
        self::assertTrue($totp->verify($code));
        self::assertFalse($totp->verify('000000'));
        self::assertStringStartsWith('otpauth://totp/', $totp->provisioningUri('ada@example.com', 'SERIXA'));
    }
}
