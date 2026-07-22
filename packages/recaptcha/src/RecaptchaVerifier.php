<?php

declare(strict_types=1);

namespace Serixa\Recaptcha;

use Serixa\Recaptcha\Contracts\RecaptchaClient;

final class RecaptchaVerifier
{
    public function __construct(
        private readonly RecaptchaClient $client,
        private readonly string $secret,
        private readonly float $minScore = 0.5,
    ) {
    }

    public static function make(RecaptchaClient $client, string $secret, float $minScore = 0.5): self
    {
        return new self($client, $secret, $minScore);
    }

    public function verify(string $response, ?string $remoteIp = null): bool
    {
        $result = $this->client->siteVerify($this->secret, $response, $remoteIp);
        if (! ($result['success'] ?? false)) {
            return false;
        }
        if (isset($result['score']) && $result['score'] < $this->minScore) {
            return false;
        }

        return true;
    }
}
