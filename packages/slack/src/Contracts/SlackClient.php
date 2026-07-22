<?php

declare(strict_types=1);

namespace Serixa\Slack\Contracts;

interface SlackClient
{
    /**
     * @return array{success: bool, id?: string, error?: string}
     */
    public function postMessage(string $channel, string $text): array;
}
