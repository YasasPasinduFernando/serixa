<?php

declare(strict_types=1);

namespace Serixa\Firebase;

use Serixa\Firebase\Contracts\FirebaseClient;
use Serixa\Firebase\Contracts\Notifier;

final class FirebaseNotifier implements Notifier
{
    public function __construct(
        private readonly FirebaseClient $client,
    ) {
    }

    public static function make(FirebaseClient $client): self
    {
        return new self($client);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $result = $this->client->send($message->to, $message->title, $message->body, $message->data);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('firebase', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'firebase');
    }
}
