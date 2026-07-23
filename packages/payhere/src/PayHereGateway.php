<?php

declare(strict_types=1);

namespace Serixa\Payhere;

use Serixa\Checkout\Order;
use Serixa\Payhere\Contracts\PayHereClient;
use Serixa\Stripe\Contracts\PaymentGateway;
use Serixa\Stripe\Exceptions\PaymentException;
use Serixa\Stripe\PaymentResult;

final class PayHereGateway implements PaymentGateway
{
    public function __construct(private readonly PayHereClient $client)
    {
    }

    public static function make(PayHereClient $client): self
    {
        return new self($client);
    }

    public function charge(int $amountMinor, string $currency, string $reference, array $options = []): PaymentResult
    {
        if ($amountMinor <= 0) {
            throw PaymentException::invalidAmount($amountMinor);
        }
        if (trim($reference) === '') {
            throw PaymentException::emptyReference();
        }

        $result = $this->client->checkout($amountMinor, strtoupper($currency), $reference, $options);
        if (! ($result['success'] ?? false)) {
            return PaymentResult::fail('payhere', (string) ($result['error'] ?? 'charge failed'));
        }

        return PaymentResult::ok((string) ($result['id'] ?? ''), 'payhere');
    }

    public function chargeOrder(Order $order, array $options = []): PaymentResult
    {
        return $this->charge($order->subtotal()->amount(), $order->subtotal()->currency(), $order->id(), $options);
    }
}
