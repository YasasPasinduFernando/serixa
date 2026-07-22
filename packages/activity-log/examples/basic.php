<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\ActivityLog\ActivityLogger;

$log = ActivityLogger::memory();
$log->log('User signed in', 'alice');
$log->log('Deal won', 'alice', 'deal', '99', ['amount' => 12000]);

echo 'count: ', $log->store()->count(), PHP_EOL;
echo 'latest: ', $log->store()->latest(1)[0]->description(), PHP_EOL;

if ($log->store()->count() === 2) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
