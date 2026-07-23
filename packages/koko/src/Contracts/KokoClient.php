<?php

declare(strict_types=1);

namespace Serixa\Koko\Contracts;

interface KokoClient
{
    /**
     * @param array<string, mixed> $options
     * @return array{success: bool, id?: string, error?: string}
     */
    public function createPayment(int $amountMinor, string $currency, string $reference, array $options = []): array;
}
