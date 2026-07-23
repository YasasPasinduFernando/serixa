<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Stripe\FakeStripeClient;
use Serixa\Stripe\StripeGateway;

$result = StripeGateway::make(new FakeStripeClient())->charge(100, 'USD', 'smoke');
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
