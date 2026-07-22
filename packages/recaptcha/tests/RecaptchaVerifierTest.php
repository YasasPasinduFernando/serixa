<?php

declare(strict_types=1);

namespace Serixa\Recaptcha\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Recaptcha\FakeRecaptchaClient;
use Serixa\Recaptcha\RecaptchaVerifier;

final class RecaptchaVerifierTest extends TestCase
{
    public function testVerify(): void
    {
        $v = RecaptchaVerifier::make(new FakeRecaptchaClient(), 'secret');
        self::assertTrue($v->verify('token'));
        self::assertFalse($v->verify('invalid'));
    }
}
