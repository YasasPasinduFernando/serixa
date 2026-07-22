<?php

declare(strict_types=1);

namespace Serixa\Twilio\Contracts;

interface TwilioClient
{
    /**
     * @return array{success: bool, id?: string, error?: string}
     */
    public function sendSms(string $to, string $from, string $body): array;
}
