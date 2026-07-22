<?php

declare(strict_types=1);

namespace Serixa\Invoice\Memory;

use Serixa\Invoice\Contracts\InvoiceStore;
use Serixa\Invoice\Invoice;

final class MemoryInvoiceStore implements InvoiceStore
{
    /** @var array<string, Invoice> */
    private array $items = [];

    public function put(Invoice $invoice): void
    {
        $this->items[$invoice->id()] = $invoice;
    }

    public function get(string $id): ?Invoice
    {
        return $this->items[$id] ?? null;
    }

    public function forCustomer(string $customerId): array
    {
        $out = [];
        foreach ($this->items as $invoice) {
            if ($invoice->customerId() === $customerId) {
                $out[] = $invoice;
            }
        }

        return $out;
    }
}
