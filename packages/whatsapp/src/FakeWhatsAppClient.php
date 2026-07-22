<?php

declare(strict_types=1);

namespace Serixa\Whatsapp;

use Serixa\Whatsapp\Contracts\WhatsAppClient;

final class FakeWhatsAppClient implements WhatsAppClient
{
    private int $seq = 0;

    public function sendText(string $to, string $body): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'wamid.' . $this->seq];
    }
}
