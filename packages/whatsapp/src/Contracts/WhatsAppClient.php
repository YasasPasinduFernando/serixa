<?php

declare(strict_types=1);

namespace Serixa\Whatsapp\Contracts;

interface WhatsAppClient
{
    /**
     * @return array{success: bool, id?: string, error?: string}
     */
    public function sendText(string $to, string $body): array;
}
