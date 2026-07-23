<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth;

use Serixa\GoogleOauth\Contracts\GoogleOAuthClient;

final class FakeGoogleOAuthClient implements GoogleOAuthClient
{
    public function buildAuthUrl(string $clientId, string $redirectUri, array $scopes, string $state): string
    {
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => implode(' ', $scopes),
            'state' => $state,
            'response_type' => 'code',
        ]);
    }

    public function exchangeCode(string $clientId, string $clientSecret, string $code, string $redirectUri): array
    {
        return [
            'access_token' => 'google-access-' . $code,
            'refresh_token' => 'google-refresh-' . $code,
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ];
    }

    public function userInfo(string $accessToken): array
    {
        return [
            'id' => 'g-1',
            'email' => 'user@gmail.com',
            'name' => 'Google User',
        ];
    }
}
