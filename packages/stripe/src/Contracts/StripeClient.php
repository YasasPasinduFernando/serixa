<?php

declare(strict_types=1);

namespace Serixa\Stripe\Contracts;

interface StripeClient
{
    /**
     * @param array<string, mixed> $options
     * @return array{success: bool, id?: string, error?: string}
     */
    public function createPaymentIntent(int $amountMinor, string $currency, string $reference, array $options = []): array;
}
