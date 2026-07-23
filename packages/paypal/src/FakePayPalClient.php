<?php

declare(strict_types=1);

namespace Serixa\Paypal;

use Serixa\Paypal\Contracts\PayPalClient;

final class FakePayPalClient implements PayPalClient
{
    private int $seq = 0;

    public function createOrder(int $amountMinor, string $currency, string $reference, array $options = []): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'PAYID-' . $this->seq];
    }
}
