<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth;

use Serixa\GoogleOauth\Exceptions\OAuthException;

final class OAuthToken
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken = '',
        public readonly int $expiresIn = 0,
        public readonly string $tokenType = 'Bearer',
    ) {
        if (trim($accessToken) === '') {
            throw OAuthException::emptyToken();
        }
    }

    /**
     * @param array{access_token: string, refresh_token?: string, expires_in?: int, token_type?: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['access_token'],
            (string) ($data['refresh_token'] ?? ''),
            (int) ($data['expires_in'] ?? 0),
            (string) ($data['token_type'] ?? 'Bearer'),
        );
    }
}
