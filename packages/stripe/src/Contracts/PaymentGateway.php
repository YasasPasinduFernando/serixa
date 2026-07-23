<?php

declare(strict_types=1);

namespace Serixa\Stripe\Contracts;

use Serixa\Checkout\Order;
use Serixa\Stripe\PaymentResult;

interface PaymentGateway
{
    /**
     * @param array<string, mixed> $options
     */
    public function charge(int $amountMinor, string $currency, string $reference, array $options = []): PaymentResult;

    /**
     * @param array<string, mixed> $options
     */
    public function chargeOrder(Order $order, array $options = []): PaymentResult;
}
