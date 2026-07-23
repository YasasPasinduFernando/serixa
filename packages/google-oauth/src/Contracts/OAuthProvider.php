<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth\Contracts;

use Serixa\GoogleOauth\OAuthToken;
use Serixa\GoogleOauth\OAuthUser;

interface OAuthProvider
{
    /**
     * @param list<string> $scopes
     */
    public function getAuthorizationUrl(string $redirectUri, array $scopes = [], string $state = ''): string;

    public function exchangeCode(string $code, string $redirectUri): OAuthToken;

    public function fetchUser(OAuthToken $token): OAuthUser;
}
