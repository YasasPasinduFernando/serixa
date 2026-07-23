<?php

declare(strict_types=1);

namespace Serixa\Stripe;

final class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly string $id = '',
        public readonly string $provider = '',
        public readonly ?string $error = null,
    ) {
    }

    public static function ok(string $id, string $provider): self
    {
        return new self(true, $id, $provider);
    }

    public static function fail(string $provider, string $error): self
    {
        return new self(false, '', $provider, $error);
    }
}
