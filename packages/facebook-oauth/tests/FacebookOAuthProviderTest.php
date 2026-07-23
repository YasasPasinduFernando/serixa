<?php

declare(strict_types=1);

namespace Serixa\FacebookOauth\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\FacebookOauth\FacebookOAuthProvider;
use Serixa\FacebookOauth\FakeFacebookOAuthClient;

final class FacebookOAuthProviderTest extends TestCase
{
    public function testAuthFlow(): void
    {
        $provider = FacebookOAuthProvider::make(new FakeFacebookOAuthClient(), 'cid', 'secret');
        $user = $provider->fetchUser($provider->exchangeCode('x', 'https://app.test/cb'));
        self::assertSame('facebook', $user->provider);
    }
}
