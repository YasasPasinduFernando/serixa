<?php

declare(strict_types=1);

namespace Serixa\Stripe;

use Serixa\Stripe\Contracts\StripeClient;

final class FakeStripeClient implements StripeClient
{
    private int $seq = 0;

    public function createPaymentIntent(int $amountMinor, string $currency, string $reference, array $options = []): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'pi_' . $this->seq];
    }
}
