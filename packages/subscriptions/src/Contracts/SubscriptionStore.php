<?php

declare(strict_types=1);

namespace Serixa\Subscriptions\Contracts;

use Serixa\Subscriptions\Plan;
use Serixa\Subscriptions\Subscription;

interface SubscriptionStore
{
    public function putPlan(Plan $plan): void;

    public function getPlan(string $id): ?Plan;

    public function put(Subscription $subscription): void;

    public function get(string $id): ?Subscription;

    /**
     * @return list<Subscription>
     */
    public function forCustomer(string $customerId): array;
}
