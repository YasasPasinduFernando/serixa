<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Subscriptions\BillingInterval;
use Serixa\Subscriptions\Plan;
use Serixa\Subscriptions\Subscriptions;
use Serixa\Subscriptions\SubscriptionStatus;

$subs = Subscriptions::memory();
$subs->registerPlan(Plan::make('growth', 'Growth', 4900, BillingInterval::Month, 1, 'USD', 7));
$sub = $subs->subscribe('sub_demo', 'user_1', 'growth');
$sub->activate();

echo 'status: ', $sub->status()->value, PHP_EOL;
echo 'plan: ', $sub->plan()->name(), PHP_EOL;

if ($sub->status() === SubscriptionStatus::Active) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
