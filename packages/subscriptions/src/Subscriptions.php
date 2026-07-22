<?php

declare(strict_types=1);

namespace Serixa\Subscriptions;

use Serixa\Subscriptions\Contracts\SubscriptionStore;
use Serixa\Subscriptions\Exceptions\SubscriptionException;
use Serixa\Subscriptions\Memory\MemorySubscriptionStore;

final class Subscriptions
{
    public function __construct(private readonly SubscriptionStore $store)
    {
    }

    public static function memory(): self
    {
        return new self(new MemorySubscriptionStore());
    }

    public function store(): SubscriptionStore
    {
        return $this->store;
    }

    public function registerPlan(Plan $plan): self
    {
        $this->store->putPlan($plan);

        return $this;
    }

    public function subscribe(string $subscriptionId, string $customerId, string $planId): Subscription
    {
        $plan = $this->store->getPlan($planId);
        if ($plan === null) {
            throw SubscriptionException::planNotFound($planId);
        }
        $subscription = Subscription::start($subscriptionId, $customerId, $plan);
        $this->store->put($subscription);

        return $subscription;
    }
}
