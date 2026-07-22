<?php

declare(strict_types=1);

namespace Serixa\WebAuthn\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Passkeys\Passkeys;
use Serixa\WebAuthn\Base64Url;
use Serixa\WebAuthn\WebAuthnOptions;

final class WebAuthnOptionsTest extends TestCase
{
    public function testRegistrationOptions(): void
    {
        $pk = Passkeys::memory();
        $challenge = $pk->beginRegistration('u1');
        $opts = WebAuthnOptions::make('example.com', 'Example', 'https://example.com')
            ->registrationOptions($challenge, 'u1', 'ada', 'Ada');

        self::assertSame($challenge->challenge(), $opts['challenge']);
        self::assertSame('example.com', $opts['rp']['id']);
        self::assertSame(Base64Url::encode('u1'), $opts['user']['id']);
    }
}
