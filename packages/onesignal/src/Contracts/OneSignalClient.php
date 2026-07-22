<?php

declare(strict_types=1);

namespace Serixa\Onesignal\Contracts;

interface OneSignalClient
{
    /**
     * @param array<string, mixed> $data
     * @return array{success: bool, id?: string, error?: string}
     */
    public function notify(string $playerId, string $title, string $body, array $data = []): array;
}
