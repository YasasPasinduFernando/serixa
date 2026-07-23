<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\GoogleOauth\FakeGoogleOAuthClient;
use Serixa\GoogleOauth\GoogleOAuthProvider;

final class GoogleOAuthProviderTest extends TestCase
{
    public function testAuthFlow(): void
    {
        $provider = GoogleOAuthProvider::make(new FakeGoogleOAuthClient(), 'cid', 'secret');
        $url = $provider->getAuthorizationUrl('https://app.test/callback', ['email'], 'xyz');
        self::assertStringContainsString('client_id=cid', $url);
        $token = $provider->exchangeCode('abc', 'https://app.test/callback');
        self::assertSame('google-access-abc', $token->accessToken);
        $user = $provider->fetchUser($token);
        self::assertSame('user@gmail.com', $user->email);
        self::assertSame('google', $user->provider);
    }
}
