<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Paypal\FakePayPalClient;
use Serixa\Paypal\PayPalGateway;

$result = PayPalGateway::make(new FakePayPalClient())->charge(100, 'USD', 'smoke');
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
