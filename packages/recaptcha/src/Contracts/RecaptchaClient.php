<?php

declare(strict_types=1);

namespace Serixa\Recaptcha\Contracts;

interface RecaptchaClient
{
    /**
     * @return array{success: bool, score?: float, action?: string, 'error-codes'?: list<string>}
     */
    public function siteVerify(string $secret, string $response, ?string $remoteIp = null): array;
}
