<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\TwoFactor\Totp;

$t = Totp::make(Totp::generateSecret());
echo $t->verify($t->now()) ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($t->verify($t->now()) ? 0 : 1);
