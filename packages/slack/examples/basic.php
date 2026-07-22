<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Firebase\NotificationMessage;
use Serixa\Slack\FakeSlackClient;
use Serixa\Slack\SlackNotifier;

$result = SlackNotifier::make(new FakeSlackClient())
    ->send(NotificationMessage::make('#general', 'Hello Serixa'));
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
