<?php

declare(strict_types=1);

namespace Serixa\Recaptcha;

use Serixa\Recaptcha\Contracts\RecaptchaClient;

final class FakeRecaptchaClient implements RecaptchaClient
{
    public function __construct(private readonly bool $success = true, private readonly float $score = 0.9)
    {
    }

    public function siteVerify(string $secret, string $response, ?string $remoteIp = null): array
    {
        if ($response === '' || $response === 'invalid') {
            return ['success' => false, 'error-codes' => ['invalid-input-response']];
        }

        return ['success' => $this->success, 'score' => $this->score, 'action' => 'submit'];
    }
}
