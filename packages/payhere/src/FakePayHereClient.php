<?php

declare(strict_types=1);

namespace Serixa\Payhere;

use Serixa\Payhere\Contracts\PayHereClient;

final class FakePayHereClient implements PayHereClient
{
    private int $seq = 0;

    public function checkout(int $amountMinor, string $currency, string $reference, array $options = []): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'PH-' . $this->seq];
    }
}
