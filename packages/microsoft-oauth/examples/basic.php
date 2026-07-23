<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\MicrosoftOauth\FakeMicrosoftOAuthClient;
use Serixa\MicrosoftOauth\MicrosoftOAuthProvider;

$p = MicrosoftOAuthProvider::make(new FakeMicrosoftOAuthClient(), 'cid', 'secret');
$ok = $p->fetchUser($p->exchangeCode('s', 'https://app.test/cb'))->provider === 'microsoft';
echo $ok ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($ok ? 0 : 1);
