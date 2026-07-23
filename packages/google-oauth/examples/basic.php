<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\GoogleOauth\FakeGoogleOAuthClient;
use Serixa\GoogleOauth\GoogleOAuthProvider;

$provider = GoogleOAuthProvider::make(new FakeGoogleOAuthClient(), 'cid', 'secret');
$token = $provider->exchangeCode('smoke', 'https://app.test/cb');
$user = $provider->fetchUser($token);
$ok = $user->email === 'user@gmail.com';
echo $ok ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($ok ? 0 : 1);
