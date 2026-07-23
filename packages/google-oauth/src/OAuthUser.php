<?php

declare(strict_types=1);

namespace Serixa\GoogleOauth;

final class OAuthUser
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $name,
        public readonly string $provider = '',
    ) {
    }
}
