<?php

declare(strict_types=1);

namespace Serixa\Tax;

final class TaxCalculator
{
    /** @var list<TaxRate> */
    private array $rates = [];

    public static function make(): self
    {
        return new self();
    }

    public function addRate(TaxRate $rate): self
    {
        $this->rates[] = $rate;

        return $this;
    }

    /**
     * @return list<TaxRate>
     */
    public function rates(): array
    {
        return $this->rates;
    }

    public function calculate(int $amount): TaxBreakdown
    {
        $lines = [];
        foreach ($this->rates as $rate) {
            $lines[] = new TaxLine($rate, $amount, $rate->apply($amount));
        }

        return new TaxBreakdown($amount, $lines);
    }
}
