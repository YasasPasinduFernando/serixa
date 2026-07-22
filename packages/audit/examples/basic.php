<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\ActivityLog\ActivityLogger;
use Serixa\Audit\AuditLogger;

$activity = ActivityLogger::memory();
$audit = AuditLogger::memory($activity);
$audit->record('login.success', 'alice', 'session', 's1');
$audit->record('permission.denied', 'alice', 'resource', 'invoices.pay');

echo 'audit: ', $audit->store()->count(), PHP_EOL;
echo 'activity: ', $activity->store()->count(), PHP_EOL;

if ($audit->store()->count() === 2 && $activity->store()->count() === 2) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
