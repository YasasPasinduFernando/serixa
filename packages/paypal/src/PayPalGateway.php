<?php

declare(strict_types=1);

namespace Serixa\Paypal;

use Serixa\Checkout\Order;
use Serixa\Paypal\Contracts\PayPalClient;
use Serixa\Stripe\Contracts\PaymentGateway;
use Serixa\Stripe\Exceptions\PaymentException;
use Serixa\Stripe\PaymentResult;

final class PayPalGateway implements PaymentGateway
{
    public function __construct(private readonly PayPalClient $client)
    {
    }

    public static function make(PayPalClient $client): self
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

        $result = $this->client->createOrder($amountMinor, strtoupper($currency), $reference, $options);
        if (! ($result['success'] ?? false)) {
            return PaymentResult::fail('paypal', (string) ($result['error'] ?? 'charge failed'));
        }

        return PaymentResult::ok((string) ($result['id'] ?? ''), 'paypal');
    }

    public function chargeOrder(Order $order, array $options = []): PaymentResult
    {
        return $this->charge($order->subtotal()->amount(), $order->subtotal()->currency(), $order->id(), $options);
    }
}
