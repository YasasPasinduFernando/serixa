<?php

declare(strict_types=1);

namespace Serixa\Discord\Contracts;

interface DiscordClient
{
    /**
     * @return array{success: bool, id?: string, error?: string}
     */
    public function executeWebhook(string $webhookUrl, string $content): array;
}
