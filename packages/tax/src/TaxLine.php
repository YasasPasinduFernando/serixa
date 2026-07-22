<?php

declare(strict_types=1);

namespace Serixa\Tax;

final class TaxLine
{
    public function __construct(
        private readonly TaxRate $rate,
        private readonly int $taxableAmount,
        private readonly int $taxAmount,
    ) {
    }

    public function rate(): TaxRate
    {
        return $this->rate;
    }

    public function taxableAmount(): int
    {
        return $this->taxableAmount;
    }

    public function taxAmount(): int
    {
        return $this->taxAmount;
    }
}
