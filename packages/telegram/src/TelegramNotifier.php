<?php

declare(strict_types=1);

namespace Serixa\Telegram;

use Serixa\Firebase\Contracts\Notifier;
use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;
use Serixa\Telegram\Contracts\TelegramClient;

final class TelegramNotifier implements Notifier
{
    public function __construct(private readonly TelegramClient $client)
    {
    }

    public static function make(TelegramClient $client): self
    {
        return new self($client);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $text = $message->title !== ''
            ? '<b>' . $message->title . '</b>' . "\n" . $message->body
            : $message->body;
        $result = $this->client->sendMessage($message->to, $text);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('telegram', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'telegram');
    }
}
