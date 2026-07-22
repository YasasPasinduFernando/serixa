<?php

declare(strict_types=1);

namespace Serixa\Firebase\Contracts;

interface FirebaseClient
{
    /**
     * @param array<string, mixed> $data
     * @return array{success: bool, id?: string, error?: string}
     */
    public function send(string $token, string $title, string $body, array $data = []): array;
}
