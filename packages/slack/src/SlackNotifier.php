<?php

declare(strict_types=1);

namespace Serixa\Slack;

use Serixa\Firebase\Contracts\Notifier;
use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;
use Serixa\Slack\Contracts\SlackClient;

final class SlackNotifier implements Notifier
{
    public function __construct(private readonly SlackClient $client)
    {
    }

    public static function make(SlackClient $client): self
    {
        return new self($client);
    }

    public function send(NotificationMessage $message): NotificationResult
    {
        $text = $message->title !== ''
            ? '*' . $message->title . '*' . "\n" . $message->body
            : $message->body;
        $result = $this->client->postMessage($message->to, $text);
        if (! ($result['success'] ?? false)) {
            return NotificationResult::fail('slack', (string) ($result['error'] ?? 'send failed'));
        }

        return NotificationResult::ok((string) ($result['id'] ?? ''), 'slack');
    }
}
