<?php

declare(strict_types=1);

namespace Serixa\Koko;

use Serixa\Checkout\Order;
use Serixa\Koko\Contracts\KokoClient;
use Serixa\Stripe\Contracts\PaymentGateway;
use Serixa\Stripe\Exceptions\PaymentException;
use Serixa\Stripe\PaymentResult;

final class KokoGateway implements PaymentGateway
{
    public function __construct(private readonly KokoClient $client)
    {
    }

    public static function make(KokoClient $client): self
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

        $result = $this->client->createPayment($amountMinor, strtoupper($currency), $reference, $options);
        if (! ($result['success'] ?? false)) {
            return PaymentResult::fail('koko', (string) ($result['error'] ?? 'charge failed'));
        }

        return PaymentResult::ok((string) ($result['id'] ?? ''), 'koko');
    }

    public function chargeOrder(Order $order, array $options = []): PaymentResult
    {
        return $this->charge($order->subtotal()->amount(), $order->subtotal()->currency(), $order->id(), $options);
    }
}
