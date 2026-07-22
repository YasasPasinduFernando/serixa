<?php

declare(strict_types=1);

namespace Serixa\Subscriptions;

use Serixa\Exception\InvalidArgumentException;

final class Plan
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly int $amount,
        private readonly BillingInterval $interval = BillingInterval::Month,
        private readonly int $intervalCount = 1,
        private readonly string $currency = 'USD',
        private readonly int $trialDays = 0,
    ) {
        if ($id === '' || $name === '') {
            throw new InvalidArgumentException('Plan id and name are required.');
        }
        if ($amount < 0 || $intervalCount < 1 || $trialDays < 0) {
            throw new InvalidArgumentException('Invalid plan amounts or intervals.');
        }
    }

    public static function make(
        string $id,
        string $name,
        int $amount,
        BillingInterval $interval = BillingInterval::Month,
        int $intervalCount = 1,
        string $currency = 'USD',
        int $trialDays = 0,
    ): self {
        return new self($id, $name, $amount, $interval, $intervalCount, strtoupper($currency), $trialDays);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function interval(): BillingInterval
    {
        return $this->interval;
    }

    public function intervalCount(): int
    {
        return $this->intervalCount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function trialDays(): int
    {
        return $this->trialDays;
    }
}
