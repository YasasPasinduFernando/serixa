<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Permissions\PermissionCatalog;
use Serixa\Rbac\Rbac;

$catalog = PermissionCatalog::make()
    ->group('leads', 'Leads', ['leads.view', 'leads.edit', 'leads.delete'])
    ->group('deals', 'Deals', ['deals.view', 'deals.manage']);

$rbac = $catalog->syncTo(Rbac::memory());
$rbac->defineRole('sales', 'Sales', ['leads.view', 'leads.edit', 'deals.view']);
$rbac->assign('alice', 'sales');

echo 'matrix rows: ', count($catalog->matrix()), PHP_EOL;
echo 'alice deals.view: ', $rbac->can('alice', 'deals.view') ? 'yes' : 'no', PHP_EOL;
echo 'alice deals.manage: ', $rbac->can('alice', 'deals.manage') ? 'yes' : 'no', PHP_EOL;

if (count($catalog->matrix()) === 5 && $rbac->can('alice', 'deals.view') && $rbac->cannot('alice', 'deals.manage')) {
    echo "SMOKE OK\n";
    exit(0);
}

fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
