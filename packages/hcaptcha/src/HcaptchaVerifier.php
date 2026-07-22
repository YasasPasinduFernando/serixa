<?php

declare(strict_types=1);

namespace Serixa\Hcaptcha;

use Serixa\Hcaptcha\Contracts\HcaptchaClient;

final class HcaptchaVerifier
{
    public function __construct(
        private readonly HcaptchaClient $client,
        private readonly string $secret,
    ) {
    }

    public static function make(HcaptchaClient $client, string $secret): self
    {
        return new self($client, $secret);
    }

    public function verify(string $response, ?string $remoteIp = null): bool
    {
        $result = $this->client->siteVerify($this->secret, $response, $remoteIp);

        return (bool) ($result['success'] ?? false);
    }
}
