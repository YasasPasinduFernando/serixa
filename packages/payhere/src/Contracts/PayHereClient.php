<?php

declare(strict_types=1);

namespace Serixa\Payhere\Contracts;

interface PayHereClient
{
    /**
     * @param array<string, mixed> $options
     * @return array{success: bool, id?: string, error?: string}
     */
    public function checkout(int $amountMinor, string $currency, string $reference, array $options = []): array;
}
