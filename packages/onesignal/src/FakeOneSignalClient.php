<?php

declare(strict_types=1);

namespace Serixa\Onesignal;

use Serixa\Onesignal\Contracts\OneSignalClient;

final class FakeOneSignalClient implements OneSignalClient
{
    private int $seq = 0;

    public function notify(string $playerId, string $title, string $body, array $data = []): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'os-' . $this->seq];
    }
}
