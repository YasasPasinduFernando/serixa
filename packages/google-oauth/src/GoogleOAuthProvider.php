<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth;

use Serixa\GoogleOauth\Contracts\GoogleOAuthClient;
use Serixa\GoogleOauth\Contracts\OAuthProvider;
use Serixa\GoogleOauth\Exceptions\OAuthException;

final class GoogleOAuthProvider implements OAuthProvider
{
    public function __construct(
        private readonly GoogleOAuthClient $client,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {
    }

    public static function make(GoogleOAuthClient $client, string $clientId, string $clientSecret): self
    {
        return new self($client, $clientId, $clientSecret);
    }

    public function getAuthorizationUrl(string $redirectUri, array $scopes = [], string $state = ''): string
    {
        if ($scopes === []) {
            $scopes = ['openid', 'email', 'profile'];
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

        return new OAuthUser((string) $data['id'], (string) $data['email'], (string) $data['name'], 'google');
    }
}
