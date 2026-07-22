<?php

declare(strict_types=1);

namespace Serixa\Hcaptcha\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Hcaptcha\FakeHcaptchaClient;
use Serixa\Hcaptcha\HcaptchaVerifier;

final class HcaptchaVerifierTest extends TestCase
{
    public function testVerify(): void
    {
        $v = HcaptchaVerifier::make(new FakeHcaptchaClient(), 'secret');
        self::assertTrue($v->verify('token'));
        self::assertFalse($v->verify('invalid'));
    }
}
