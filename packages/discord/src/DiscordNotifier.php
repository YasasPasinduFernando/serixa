<?php

declare(strict_types=1);

namespace Serixa\Discord;

use Serixa\Discord\Contracts\DiscordClient;
use Serixa\Firebase\Contracts\Notifier;
use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;

final class DiscordNotifier implements Notifier
{
    public function __construct(private readonly DiscordClient $client)
    {
    }

    public static function make(DiscordClient $client): self
    {
        return new self($client);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $content = $message->title !== ''
            ? '**' . $message->title . '**' . "\n" . $message->body
            : $message->body;
        $result = $this->client->executeWebhook($message->to, $content);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('discord', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'discord');
    }
}
