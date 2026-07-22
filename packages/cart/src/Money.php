<?php

declare(strict_types=1);

namespace Serixa\Cart;

use Serixa\Exception\InvalidArgumentException;

/**
 * Money in minor units (e.g. cents) with ISO currency code.
 */
final class Money
{
    public function __construct(
        private readonly int $amount,
        private readonly string $currency = 'USD',
    ) {
        if ($currency === '' || strlen($currency) !== 3) {
            throw new InvalidArgumentException('Currency must be a 3-letter ISO code.');
        }
    }

    public static function of(int $amount, string $currency = 'USD'): self
    {
        return new self($amount, strtoupper($currency));
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function multiply(int $quantity): self
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative.');
        }

        return new self($this->amount * $quantity, $this->currency);
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException(sprintf(
                'Currency mismatch: %s vs %s.',
                $this->currency,
                $other->currency,
            ));
        }
    }
}
