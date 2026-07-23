<?php

declare(strict_types=1);

namespace Serixa\Koko;

use Serixa\Koko\Contracts\KokoClient;

final class FakeKokoClient implements KokoClient
{
    private int $seq = 0;

    public function createPayment(int $amountMinor, string $currency, string $reference, array $options = []): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'koko-' . $this->seq];
    }
}
