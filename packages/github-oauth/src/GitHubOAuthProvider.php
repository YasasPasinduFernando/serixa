<?php

declare(strict_types=1);

namespace Serixa\GithubOauth;

use Serixa\GithubOauth\Contracts\GitHubOAuthClient;
use Serixa\GoogleOauth\Contracts\OAuthProvider;
use Serixa\GoogleOauth\Exceptions\OAuthException;
use Serixa\GoogleOauth\OAuthToken;
use Serixa\GoogleOauth\OAuthUser;

final class GitHubOAuthProvider implements OAuthProvider
{
    public function __construct(
        private readonly GitHubOAuthClient $client,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {
    }

    public static function make(GitHubOAuthClient $client, string $clientId, string $clientSecret): self
    {
        return new self($client, $clientId, $clientSecret);
    }

    public function getAuthorizationUrl(string $redirectUri, array $scopes = [], string $state = ''): string
    {
        if ($scopes === []) {
            $scopes = ['read:user', 'user:email'];
        }

        return $this->client->buildAuthUrl($this->clientId, $redirectUri, $scopes, $state);
    }

    public function exchangeCode(string $code, string $redirectUri): OAuthToken
    {
        if (trim($code) === '') {
            throw OAuthException::emptyCode();
        }

        return OAuthToken::fromArray(
            $this->client->exchangeCode($this->clientId, $this->clientSecret, $code, $redirectUri),
        );
    }

    public function fetchUser(OAuthToken $token): OAuthUser
    {
        $data = $this->client->userInfo($token->accessToken);

        return new OAuthUser((string) $data['id'], (string) $data['email'], (string) $data['name'], 'github');
    }
}
