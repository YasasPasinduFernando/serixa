<?php

declare(strict_types=1);

namespace Serixa\Twilio;

use Serixa\Twilio\Contracts\TwilioClient;

final class FakeTwilioClient implements TwilioClient
{
    private int $seq = 0;

    public function sendSms(string $to, string $from, string $body): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'SM' . $this->seq];
    }
}
