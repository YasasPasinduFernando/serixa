<?php

declare(strict_types=1);

namespace Serixa\MicrosoftOauth;

use Serixa\MicrosoftOauth\Contracts\MicrosoftOAuthClient;

final class FakeMicrosoftOAuthClient implements MicrosoftOAuthClient
{
    public function buildAuthUrl(string $clientId, string $redirectUri, array $scopes, string $state): string
    {
        return 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . http_build_query([
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
            'access_token' => 'ms-access-' . $code,
            'refresh_token' => 'ms-refresh-' . $code,
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ];
    }

    public function userInfo(string $accessToken): array
    {
        return ['id' => 'ms-1', 'email' => 'user@outlook.com', 'name' => 'Microsoft User'];
    }
}
