<?php

declare(strict_types=1);

namespace Serixa\Firebase;

use Serixa\Firebase\Contracts\FirebaseClient;

final class FakeFirebaseClient implements FirebaseClient
{
    /** @var list<array{token: string, title: string, body: string, data: array<string, mixed>}> */
    private array $sent = [];

    private int $seq = 0;

    public function send(string $token, string $title, string $body, array $data = []): array
    {
        $this->sent[] = compact('token', 'title', 'body', 'data');
        ++$this->seq;

        return ['success' => true, 'id' => 'fcm-' . $this->seq];
    }

    /**
     * @return list<array{token: string, title: string, body: string, data: array<string, mixed>}>
     */
    public function sent(): array
    {
        return $this->sent;
    }
}
