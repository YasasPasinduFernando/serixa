<?php

declare(strict_types=1);

namespace Serixa\Hcaptcha\Contracts;

interface HcaptchaClient
{
    /**
     * @return array{success: bool, 'error-codes'?: list<string>}
     */
    public function siteVerify(string $secret, string $response, ?string $remoteIp = null): array;
}
