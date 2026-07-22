<?php

declare(strict_types=1);

namespace Serixa\Hcaptcha;

use Serixa\Hcaptcha\Contracts\HcaptchaClient;

final class FakeHcaptchaClient implements HcaptchaClient
{
    public function __construct(private readonly bool $success = true)
    {
    }

    public function siteVerify(string $secret, string $response, ?string $remoteIp = null): array
    {
        if ($response === '' || $response === 'invalid') {
            return ['success' => false, 'error-codes' => ['invalid-input-response']];
        }

        return ['success' => $this->success];
    }
}
