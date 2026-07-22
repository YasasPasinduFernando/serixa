<?php

declare(strict_types=1);

namespace Serixa\Invoice\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Invoice\Invoice;
use Serixa\Invoice\InvoiceLine;
use Serixa\Invoice\InvoiceStatus;

final class InvoiceTest extends TestCase
{
    public function testIssueAndPay(): void
    {
        $invoice = Invoice::make('inv-1', 'cust-1')
            ->addLine(InvoiceLine::make('Consulting', 15000, 2))
            ->issue()
            ->markPaid();

        self::assertSame(InvoiceStatus::Paid, $invoice->status());
        self::assertSame(30000, $invoice->subtotal());
    }

    public function testVoidDraft(): void
    {
        $invoice = Invoice::make('inv-2', 'cust-1')->void();
        self::assertSame(InvoiceStatus::Void, $invoice->status());
    }
}
