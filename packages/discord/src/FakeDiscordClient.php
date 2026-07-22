<?php

declare(strict_types=1);

namespace Serixa\Discord;

use Serixa\Discord\Contracts\DiscordClient;

final class FakeDiscordClient implements DiscordClient
{
    private int $seq = 0;

    public function executeWebhook(string $webhookUrl, string $content): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'discord-' . $this->seq];
    }
}
