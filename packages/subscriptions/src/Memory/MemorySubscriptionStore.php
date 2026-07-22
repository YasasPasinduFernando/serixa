<?php

declare(strict_types=1);

namespace Serixa\Subscriptions\Memory;

use Serixa\Subscriptions\Contracts\SubscriptionStore;
use Serixa\Subscriptions\Plan;
use Serixa\Subscriptions\Subscription;

final class MemorySubscriptionStore implements SubscriptionStore
{
    /** @var array<string, Plan> */
    private array $plans = [];

    /** @var array<string, Subscription> */
    private array $subscriptions = [];

    public function putPlan(Plan $plan): void
    {
        $this->plans[$plan->id()] = $plan;
    }

    public function getPlan(string $id): ?Plan
    {
        return $this->plans[$id] ?? null;
    }

    public function put(Subscription $subscription): void
    {
        $this->subscriptions[$subscription->id()] = $subscription;
    }

    public function get(string $id): ?Subscription
    {
        return $this->subscriptions[$id] ?? null;
    }

    public function forCustomer(string $customerId): array
    {
        $out = [];
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->customerId() === $customerId) {
                $out[] = $subscription;
            }
        }

        return $out;
    }
}
