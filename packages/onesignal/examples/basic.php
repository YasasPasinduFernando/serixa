<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Firebase\NotificationMessage;
use Serixa\Onesignal\FakeOneSignalClient;
use Serixa\Onesignal\OneSignalNotifier;

$result = OneSignalNotifier::make(new FakeOneSignalClient())
    ->send(NotificationMessage::make('player', 'Hello Serixa'));
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
