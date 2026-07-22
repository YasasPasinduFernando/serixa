<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Organization\Organizations;
use Serixa\Organization\OrgContext;

$orgs = Organizations::memory();
$orgs->create('serixa', 'SERIXA Labs');
$orgs->addMember('serixa', 'yasas', 'owner');
$orgs->addMember('serixa', 'dev', 'member');

$ctx = new OrgContext($orgs->store());
$ctx->setCurrent('serixa');

echo 'org: ', $ctx->requireCurrent()->name(), PHP_EOL;
echo 'members: ', count($orgs->store()->membersOf('serixa')), PHP_EOL;

if ($orgs->isMember('serixa', 'yasas') && count($orgs->store()->membersOf('serixa')) === 2) {
    echo "SMOKE OK\n";
    exit(0);
}
fwrite(STDERR, "SMOKE FAIL\n");
exit(1);
