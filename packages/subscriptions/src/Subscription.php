<?php

declare(strict_types=1);

namespace Serixa\Subscriptions;

use DateInterval;
use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Subscriptions\Exceptions\SubscriptionException;

final class Subscription
{
    private SubscriptionStatus $status;

    private readonly DateTimeImmutable $startedAt;

    private DateTimeImmutable $currentPeriodEnd;

    private ?DateTimeImmutable $canceledAt = null;

    public function __construct(
        private readonly string $id,
        private readonly string $customerId,
        private readonly Plan $plan,
        ?DateTimeImmutable $startedAt = null,
        ?SubscriptionStatus $status = null,
        ?DateTimeImmutable $currentPeriodEnd = null,
    ) {
        if ($id === '' || $customerId === '') {
            throw new InvalidArgumentException('Subscription id and customerId are required.');
        }

        $this->startedAt = $startedAt ?? new DateTimeImmutable();
        $this->status = $status ?? ($plan->trialDays() > 0 ? SubscriptionStatus::Trialing : SubscriptionStatus::Active);
        $this->currentPeriodEnd = $currentPeriodEnd ?? $this->periodEndFrom($this->startedAt, $this->status === SubscriptionStatus::Trialing);
    }

    public static function start(string $id, string $customerId, Plan $plan, ?DateTimeImmutable $at = null): self
    {
        return new self($id, $customerId, $plan, $at);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function customerId(): string
    {
        return $this->customerId;
    }

    public function plan(): Plan
    {
        return $this->plan;
    }

    public function status(): SubscriptionStatus
    {
        return $this->status;
    }

    public function startedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function currentPeriodEnd(): DateTimeImmutable
    {
        return $this->currentPeriodEnd;
    }

    public function canceledAt(): ?DateTimeImmutable
    {
        return $this->canceledAt;
    }

    public function activate(): self
    {
        $this->assertNotCanceled();
        $this->status = SubscriptionStatus::Active;

        return $this;
    }

    public function markPastDue(): self
    {
        $this->assertNotCanceled();
        $this->status = SubscriptionStatus::PastDue;

        return $this;
    }

    public function cancel(?DateTimeImmutable $at = null): self
    {
        $this->assertNotCanceled();
        $this->status = SubscriptionStatus::Canceled;
        $this->canceledAt = $at ?? new DateTimeImmutable();

        return $this;
    }

    public function renew(?DateTimeImmutable $from = null): self
    {
        $this->assertNotCanceled();
        $from ??= $this->currentPeriodEnd;
        $this->currentPeriodEnd = $this->periodEndFrom($from, false);
        $this->status = SubscriptionStatus::Active;

        return $this;
    }

    private function assertNotCanceled(): void
    {
        if ($this->status === SubscriptionStatus::Canceled) {
            throw SubscriptionException::alreadyCanceled($this->id);
        }
    }

    private function periodEndFrom(DateTimeImmutable $from, bool $useTrial): DateTimeImmutable
    {
        if ($useTrial && $this->plan->trialDays() > 0) {
            return $from->add(new DateInterval('P' . $this->plan->trialDays() . 'D'));
        }

        $count = $this->plan->intervalCount();
        $spec = match ($this->plan->interval()) {
            BillingInterval::Day => 'P' . $count . 'D',
            BillingInterval::Week => 'P' . ($count * 7) . 'D',
            BillingInterval::Month => 'P' . $count . 'M',
            BillingInterval::Year => 'P' . $count . 'Y',
        };

        return $from->add(new DateInterval($spec));
    }
}
