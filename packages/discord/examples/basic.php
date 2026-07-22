<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Discord\DiscordNotifier;
use Serixa\Discord\FakeDiscordClient;
use Serixa\Firebase\NotificationMessage;

$result = DiscordNotifier::make(new FakeDiscordClient())
    ->send(NotificationMessage::make('https://discord.example/webhook', 'Hello Serixa'));
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
