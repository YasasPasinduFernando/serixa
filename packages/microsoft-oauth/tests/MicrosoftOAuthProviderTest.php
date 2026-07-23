<?php

declare(strict_types=1);

namespace Serixa\MicrosoftOauth\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\MicrosoftOauth\FakeMicrosoftOAuthClient;
use Serixa\MicrosoftOauth\MicrosoftOAuthProvider;

final class MicrosoftOAuthProviderTest extends TestCase
{
    public function testAuthFlow(): void
    {
        $provider = MicrosoftOAuthProvider::make(new FakeMicrosoftOAuthClient(), 'cid', 'secret');
        $user = $provider->fetchUser($provider->exchangeCode('x', 'https://app.test/cb'));
        self::assertSame('microsoft', $user->provider);
    }
}
