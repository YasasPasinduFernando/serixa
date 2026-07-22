<?php

declare(strict_types=1);

namespace Serixa\Telegram\Contracts;

interface TelegramClient
{
    /**
     * @return array{success: bool, id?: string, error?: string}
     */
    public function sendMessage(string $chatId, string $text): array;
}
