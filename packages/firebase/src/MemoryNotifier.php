<?php

declare(strict_types=1);

namespace Serixa\Firebase;

use Serixa\Firebase\Contracts\Notifier;

/**
 * In-memory notifier for tests and offline demos.
 */
final class MemoryNotifier implements Notifier
{
    /** @var list<NotificationMessage> */
    private array $sent = [];

    private int $seq = 0;

    public function send(NotificationMessage $message): NotificationResult
    {
        $this->sent[] = $message;
        ++$this->seq;

        return NotificationResult::ok('mem-' . $this->seq, 'memory');
    }

    /**
     * @return list<NotificationMessage>
     */
    public function sent(): array
    {
        return $this->sent;
    }
}
