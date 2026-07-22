<?php

declare(strict_types=1);

namespace Serixa\Tax;

final class TaxBreakdown
{
    /**
     * @param list<TaxLine> $lines
     */
    public function __construct(
        private readonly int $taxableAmount,
        private readonly array $lines,
    ) {
    }

    public function taxableAmount(): int
    {
        return $this->taxableAmount;
    }

    /**
     * @return list<TaxLine>
     */
    public function lines(): array
    {
        return $this->lines;
    }

    public function taxTotal(): int
    {
        $sum = 0;
        foreach ($this->lines as $line) {
            $sum += $line->taxAmount();
        }

        return $sum;
    }

    public function grandTotal(): int
    {
        return $this->taxableAmount + $this->taxTotal();
    }
}
