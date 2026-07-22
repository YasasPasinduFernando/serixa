<?php

declare(strict_types=1);

namespace Serixa\Onesignal;

use Serixa\Firebase\Contracts\Notifier;
use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;
use Serixa\Onesignal\Contracts\OneSignalClient;

final class OneSignalNotifier implements Notifier
{
    public function __construct(private readonly OneSignalClient $client)
    {
    }

    public static function make(OneSignalClient $client): self
    {
        return new self($client);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $result = $this->client->notify($message->to, $message->title, $message->body, $message->data);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('onesignal', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'onesignal');
    }
}
