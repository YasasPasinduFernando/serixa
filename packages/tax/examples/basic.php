<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Tax\TaxCalculator;
use Serixa\Tax\TaxRate;

$calc = TaxCalculator::make()->addRate(TaxRate::percent('vat', 'VAT', 10));
$result = $calc->calculate(5000);

echo 'tax: ', $result->taxTotal(), PHP_EOL;
echo 'total: ', $result->grandTotal(), PHP_EOL;

if ($result->taxTotal() === 500 && $result->grandTotal() === 5500) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
