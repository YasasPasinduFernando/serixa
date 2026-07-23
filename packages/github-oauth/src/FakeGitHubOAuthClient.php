<?php

declare(strict_types=1);

namespace Serixa\GithubOauth;

use Serixa\GithubOauth\Contracts\GitHubOAuthClient;

final class FakeGitHubOAuthClient implements GitHubOAuthClient
{
    public function buildAuthUrl(string $clientId, string $redirectUri, array $scopes, string $state): string
    {
        return 'https://github.com/login/oauth/authorize?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => implode(' ', $scopes),
            'state' => $state,
        ]);
    }

    public function exchangeCode(string $clientId, string $clientSecret, string $code, string $redirectUri): array
    {
        return ['access_token' => 'gh-access-' . $code, 'token_type' => 'bearer', 'expires_in' => 0];
    }

    public function userInfo(string $accessToken): array
    {
        return ['id' => '42', 'email' => 'dev@github.com', 'name' => 'GitHub User'];
    }
}
