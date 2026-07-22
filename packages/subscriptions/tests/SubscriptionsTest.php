<?php

declare(strict_types=1);

namespace Serixa\Subscriptions\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Subscriptions\BillingInterval;
use Serixa\Subscriptions\Plan;
use Serixa\Subscriptions\Subscriptions;
use Serixa\Subscriptions\SubscriptionStatus;

final class SubscriptionsTest extends TestCase
{
    public function testTrialThenActivate(): void
    {
        $subs = Subscriptions::memory();
        $subs->registerPlan(Plan::make('pro', 'Pro', 2900, BillingInterval::Month, 1, 'USD', 14));
        $sub = $subs->subscribe('sub-1', 'cust-1', 'pro');

        self::assertSame(SubscriptionStatus::Trialing, $sub->status());
        $sub->activate();
        self::assertSame(SubscriptionStatus::Active, $sub->status());
    }

    public function testCancel(): void
    {
        $subs = Subscriptions::memory();
        $subs->registerPlan(Plan::make('basic', 'Basic', 900));
        $sub = $subs->subscribe('sub-2', 'cust-2', 'basic');
        $sub->cancel();
        self::assertSame(SubscriptionStatus::Canceled, $sub->status());
        self::assertNotNull($sub->canceledAt());
    }
}
