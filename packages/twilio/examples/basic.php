<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Firebase\NotificationMessage;
use Serixa\Twilio\FakeTwilioClient;
use Serixa\Twilio\TwilioNotifier;

$result = TwilioNotifier::make(new FakeTwilioClient(), '+10000000000')
    ->send(NotificationMessage::make('+15551234567', 'Hello Serixa'));
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
