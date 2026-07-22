<?php

declare(strict_types=1);

namespace Serixa\Whatsapp;

use Serixa\Firebase\Contracts\Notifier;
use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;
use Serixa\Whatsapp\Contracts\WhatsAppClient;

final class WhatsAppNotifier implements Notifier
{
    public function __construct(private readonly WhatsAppClient $client)
    {
    }

    public static function make(WhatsAppClient $client): self
    {
        return new self($client);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $body = $message->title !== '' ? $message->title . ': ' . $message->body : $message->body;
        $result = $this->client->sendText($message->to, $body);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('whatsapp', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'whatsapp');
    }
}
