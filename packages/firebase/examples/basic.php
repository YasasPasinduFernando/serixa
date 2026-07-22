<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Firebase\FakeFirebaseClient;
use Serixa\Firebase\FirebaseNotifier;
use Serixa\Firebase\NotificationMessage;

$notifier = FirebaseNotifier::make(new FakeFirebaseClient());
$result = $notifier->send(NotificationMessage::make('token', 'Hello Serixa', 'Hi'));
echo $result->success ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($result->success ? 0 : 1);
