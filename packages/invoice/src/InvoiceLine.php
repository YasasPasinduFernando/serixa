<?php

declare(strict_types=1);

namespace Serixa\Invoice;

use Serixa\Exception\InvalidArgumentException;

final class InvoiceLine
{
    public function __construct(
        private readonly string $description,
        private readonly int $unitAmount,
        private readonly int $quantity = 1,
        private readonly string $currency = 'USD',
    ) {
        if ($description === '') {
            throw new InvalidArgumentException('Invoice line description is required.');
        }
        if ($quantity < 1) {
            throw new InvalidArgumentException('Invoice line quantity must be at least 1.');
        }
    }

    public static function make(string $description, int $unitAmount, int $quantity = 1, string $currency = 'USD'): self
    {
        return new self($description, $unitAmount, $quantity, strtoupper($currency));
    }

    public function description(): string
    {
        return $this->description;
    }

    public function unitAmount(): int
    {
        return $this->unitAmount;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function total(): int
    {
        return $this->unitAmount * $this->quantity;
    }
}
