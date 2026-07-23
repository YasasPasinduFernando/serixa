<?php

declare(strict_types=1);

namespace Serixa\FacebookOauth;

use Serixa\FacebookOauth\Contracts\FacebookOAuthClient;

final class FakeFacebookOAuthClient implements FacebookOAuthClient
{
    public function buildAuthUrl(string $clientId, string $redirectUri, array $scopes, string $state): string
    {
        return 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => implode(',', $scopes),
            'state' => $state,
        ]);
    }

    public function exchangeCode(string $clientId, string $clientSecret, string $code, string $redirectUri): array
    {
        return ['access_token' => 'fb-access-' . $code, 'token_type' => 'bearer', 'expires_in' => 3600];
    }

    public function userInfo(string $accessToken): array
    {
        return ['id' => 'fb-1', 'email' => 'user@facebook.com', 'name' => 'Facebook User'];
    }
}
