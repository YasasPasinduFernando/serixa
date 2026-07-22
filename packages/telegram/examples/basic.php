<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Firebase\NotificationMessage;
use Serixa\Telegram\FakeTelegramClient;
use Serixa\Telegram\TelegramNotifier;

$result = TelegramNotifier::make(new FakeTelegramClient())
    ->send(NotificationMessage::make('12345', 'Hello Serixa'));
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
