<?php

declare(strict_types=1);

namespace Serixa\Invoice;

use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Invoice\Exceptions\InvoiceException;

final class Invoice
{
    /** @var list<InvoiceLine> */
    private array $lines = [];

    private InvoiceStatus $status = InvoiceStatus::Draft;

    private ?DateTimeImmutable $issuedAt = null;

    private ?DateTimeImmutable $paidAt = null;

    public function __construct(
        private readonly string $id,
        private readonly string $customerId,
        private readonly string $currency = 'USD',
    ) {
        if ($id === '' || $customerId === '') {
            throw new InvalidArgumentException('Invoice id and customerId are required.');
        }
    }

    public static function make(string $id, string $customerId, string $currency = 'USD'): self
    {
        return new self($id, $customerId, strtoupper($currency));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function customerId(): string
    {
        return $this->customerId;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function status(): InvoiceStatus
    {
        return $this->status;
    }

    public function addLine(InvoiceLine $line): self
    {
        $this->assertMutable();
        if ($line->currency() !== $this->currency) {
            throw InvoiceException::currencyMismatch($this->currency, $line->currency());
        }
        $this->lines[] = $line;

        return $this;
    }

    /**
     * @return list<InvoiceLine>
     */
    public function lines(): array
    {
        return $this->lines;
    }

    public function subtotal(): int
    {
        $sum = 0;
        foreach ($this->lines as $line) {
            $sum += $line->total();
        }

        return $sum;
    }

    public function issue(?DateTimeImmutable $at = null): self
    {
        $this->assertMutable();
        if ($this->lines === []) {
            throw InvoiceException::emptyInvoice();
        }
        $this->status = InvoiceStatus::Issued;
        $this->issuedAt = $at ?? new DateTimeImmutable();

        return $this;
    }

    public function markPaid(?DateTimeImmutable $at = null): self
    {
        if ($this->status !== InvoiceStatus::Issued) {
            throw InvoiceException::invalidTransition($this->status->value, InvoiceStatus::Paid->value);
        }
        $this->status = InvoiceStatus::Paid;
        $this->paidAt = $at ?? new DateTimeImmutable();

        return $this;
    }

    public function void(): self
    {
        if ($this->status === InvoiceStatus::Paid) {
            throw InvoiceException::invalidTransition($this->status->value, InvoiceStatus::Void->value);
        }
        $this->status = InvoiceStatus::Void;

        return $this;
    }

    public function issuedAt(): ?DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function paidAt(): ?DateTimeImmutable
    {
        return $this->paidAt;
    }

    private function assertMutable(): void
    {
        if ($this->status !== InvoiceStatus::Draft) {
            throw InvoiceException::notDraft($this->id);
        }
    }
}
