<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Invoice\Invoice;
use Serixa\Invoice\InvoiceLine;
use Serixa\Invoice\InvoiceStatus;

$invoice = Invoice::make('INV-100', 'acme')
    ->addLine(InvoiceLine::make('Support plan', 9900))
    ->issue()
    ->markPaid();

echo 'status: ', $invoice->status()->value, PHP_EOL;
echo 'total: ', $invoice->subtotal(), PHP_EOL;

if ($invoice->status() === InvoiceStatus::Paid && $invoice->subtotal() === 9900) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
