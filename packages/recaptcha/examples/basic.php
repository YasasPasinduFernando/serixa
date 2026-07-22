<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Recaptcha\FakeRecaptchaClient;
use Serixa\Recaptcha\RecaptchaVerifier;

$v = RecaptchaVerifier::make(new FakeRecaptchaClient(), 's');
echo $v->verify('ok') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($v->verify('ok') ? 0 : 1);
