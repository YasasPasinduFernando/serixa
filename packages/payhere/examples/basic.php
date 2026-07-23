<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Payhere\FakePayHereClient;
use Serixa\Payhere\PayHereGateway;

$result = PayHereGateway::make(new FakePayHereClient())->charge(100, 'LKR', 'smoke');
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
