<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth\Contracts;

interface GoogleOAuthClient
{
    /**
     * @param list<string> $scopes
     */
    public function buildAuthUrl(string $clientId, string $redirectUri, array $scopes, string $state): string;

    /**
     * @return array{access_token: string, refresh_token?: string, expires_in?: int, token_type?: string}
     */
    public function exchangeCode(string $clientId, string $clientSecret, string $code, string $redirectUri): array;

    /**
     * @return array{id: string, email: string, name: string}
     */
    public function userInfo(string $accessToken): array;
}
