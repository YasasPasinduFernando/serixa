<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Passkeys\Passkeys;
use Serixa\WebAuthn\WebAuthnOptions;

$pk = Passkeys::memory();
$c = $pk->beginRegistration('u1');
$opts = WebAuthnOptions::make('localhost', 'Demo', 'http://localhost')
    ->registrationOptions($c, 'u1', 'demo', 'Demo');
echo isset($opts['challenge']) ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit(isset($opts['challenge']) ? 0 : 1);
