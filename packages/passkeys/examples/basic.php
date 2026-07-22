<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Passkeys\Passkeys;

$pk = Passkeys::memory();
$c = $pk->beginRegistration('u1');
$pk->register('c1', 'u1', 'key', $c->id());
echo count($pk->credentialsFor('u1')) === 1 ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(count($pk->credentialsFor('u1')) === 1 ? 0 : 1);
