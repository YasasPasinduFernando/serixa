<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Hcaptcha\FakeHcaptchaClient;
use Serixa\Hcaptcha\HcaptchaVerifier;

$v = HcaptchaVerifier::make(new FakeHcaptchaClient(), 's');
echo $v->verify('ok') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($v->verify('ok') ? 0 : 1);
