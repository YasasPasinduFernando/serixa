<?php

declare(strict_types=1);

namespace Serixa\Invoice\Contracts;

use Serixa\Invoice\Invoice;

interface InvoiceStore
{
    public function put(Invoice $invoice): void;

    public function get(string $id): ?Invoice;

    /**
     * @return list<Invoice>
     */
    public function forCustomer(string $customerId): array;
}
