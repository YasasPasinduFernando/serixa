<?php

declare(strict_types=1);

namespace Serixa\Invoice\Exceptions;

use Serixa\Exception\SerixaException;

final class InvoiceException extends SerixaException
{
    public static function notDraft(string $id): self
    {
        return new self(sprintf('Invoice [%s] is not a draft and cannot be modified.', $id));
    }

    public static function emptyInvoice(): self
    {
        return new self('Cannot issue an invoice with no lines.');
    }

    public static function currencyMismatch(string $invoice, string $line): self
    {
        return new self(sprintf('Invoice currency [%s] does not match line currency [%s].', $invoice, $line));
    }

    public static function invalidTransition(string $from, string $to): self
    {
        return new self(sprintf('Cannot transition invoice from [%s] to [%s].', $from, $to));
    }
}
