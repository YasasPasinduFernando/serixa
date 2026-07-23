<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\FacebookOauth\FacebookOAuthProvider;
use Serixa\FacebookOauth\FakeFacebookOAuthClient;

$p = FacebookOAuthProvider::make(new FakeFacebookOAuthClient(), 'cid', 'secret');
$ok = $p->fetchUser($p->exchangeCode('s', 'https://app.test/cb'))->provider === 'facebook';
echo $ok ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($ok ? 0 : 1);
