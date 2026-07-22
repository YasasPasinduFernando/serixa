<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Organization\Organizations;
use Serixa\Teams\Teams;

$orgs = Organizations::memory();
$orgs->create('acme', 'Acme');
$teams = Teams::memory($orgs->store());
$teams->create('eng', 'acme', 'Engineering');
$teams->addMember('eng', 'dev1', 'lead');

echo 'teams: ', count($teams->forOrganization('acme')), PHP_EOL;
echo 'member: ', $teams->isMember('eng', 'dev1') ? 'yes' : 'no', PHP_EOL;

if ($teams->isMember('eng', 'dev1')) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
