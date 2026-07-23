<?php

declare(strict_types=1);

namespace Serixa\Paypal\Contracts;

interface PayPalClient
{
    /**
     * @param array<string, mixed> $options
     * @return array{success: bool, id?: string, error?: string}
     */
    public function createOrder(int $amountMinor, string $currency, string $reference, array $options = []): array;
}
