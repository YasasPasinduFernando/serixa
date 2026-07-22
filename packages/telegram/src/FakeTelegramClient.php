<?php

declare(strict_types=1);

namespace Serixa\Telegram;

use Serixa\Telegram\Contracts\TelegramClient;

final class FakeTelegramClient implements TelegramClient
{
    private int $seq = 0;

    public function sendMessage(string $chatId, string $text): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => (string) $this->seq];
    }
}
