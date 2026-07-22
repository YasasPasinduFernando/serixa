<?php

declare(strict_types=1);

namespace Serixa\Twilio;

use Serixa\Firebase\Contracts\Notifier;
use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;
use Serixa\Twilio\Contracts\TwilioClient;

final class TwilioNotifier implements Notifier
{
    public function __construct(
        private readonly TwilioClient $client,
        private readonly string $from,
    ) {
    }

    public static function make(TwilioClient $client, string $from): self
    {
        return new self($client, $from);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $body = $message->title !== '' ? $message->title . ': ' . $message->body : $message->body;
        $result = $this->client->sendSms($message->to, $this->from, $body);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('twilio', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'twilio');
    }
}
