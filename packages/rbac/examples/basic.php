<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Rbac\Rbac;
use Serixa\Rbac\SimpleSubject;

$rbac = Rbac::memory();
$rbac->definePermission('leads.view', 'View leads');
$rbac->definePermission('leads.edit', 'Edit leads');
$rbac->defineRole('sales', 'Sales', ['leads.view', 'leads.edit']);
$rbac->defineRole('viewer', 'Viewer', ['leads.view']);

$alice = SimpleSubject::make('alice');
$bob = SimpleSubject::make('bob');
$rbac->assign($alice, 'sales');
$rbac->assign($bob, 'viewer');

$lines = [
    'alice can edit: ' . ($rbac->can($alice, 'leads.edit') ? 'yes' : 'no'),
    'bob can edit: ' . ($rbac->can($bob, 'leads.edit') ? 'yes' : 'no'),
    'bob can view: ' . ($rbac->can($bob, 'leads.view') ? 'yes' : 'no'),
];

foreach ($lines as $line) {
    echo $line, PHP_EOL;
}

if ($rbac->cannot($bob, 'leads.edit') && $rbac->can($alice, 'leads.edit')) {
    echo "SMOKE OK\n";
    exit(0);
}

fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
