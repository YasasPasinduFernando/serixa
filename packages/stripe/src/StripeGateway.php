<?php

declare(strict_types=1);

namespace Serixa\Stripe;

use Serixa\Checkout\Order;
use Serixa\Stripe\Contracts\PaymentGateway;
use Serixa\Stripe\Contracts\StripeClient;
use Serixa\Stripe\Exceptions\PaymentException;

final class StripeGateway implements PaymentGateway
{
    public function __construct(private readonly StripeClient $client)
    {
    }

    public static function make(StripeClient $client): self
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

        $result = $this->client->createPaymentIntent($amountMinor, strtoupper($currency), $reference, $options);
        if (! ($result['success'] ?? false)) {
            return PaymentResult::fail('stripe', (string) ($result['error'] ?? 'charge failed'));
        }

        return PaymentResult::ok((string) ($result['id'] ?? ''), 'stripe');
    }

    public function chargeOrder(Order $order, array $options = []): PaymentResult
    {
        return $this->charge($order->subtotal()->amount(), $order->subtotal()->currency(), $order->id(), $options);
    }
}
