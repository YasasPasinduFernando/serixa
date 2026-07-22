<?php

declare(strict_types=1);

namespace Serixa\Tax\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Tax\TaxCalculator;
use Serixa\Tax\TaxRate;

final class TaxCalculatorTest extends TestCase
{
    public function testPercentVat(): void
    {
        $calc = TaxCalculator::make()
            ->addRate(TaxRate::percent('vat', 'VAT', 15.0, 'LK'));

        $result = $calc->calculate(10000);
        self::assertSame(1500, $result->taxTotal());
        self::assertSame(11500, $result->grandTotal());
    }

    public function testMultipleRates(): void
    {
        $calc = TaxCalculator::make()
            ->addRate(TaxRate::make('state', 'State', 500))
            ->addRate(TaxRate::make('city', 'City', 250));

        $result = $calc->calculate(8000);
        self::assertSame(600, $result->taxTotal());
    }
}
