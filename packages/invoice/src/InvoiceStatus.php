<?php

declare(strict_types=1);

namespace Serixa\Invoice;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Issued = 'issued';
    case Paid = 'paid';
    case Void = 'void';
}
