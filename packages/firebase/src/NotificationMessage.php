<?php

declare(strict_types=1);

namespace Serixa\Firebase;

use Serixa\Firebase\Exceptions\NotificationException;

final class NotificationMessage
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public readonly string $to,
        public readonly string $body,
        public readonly string $title = '',
        public readonly array $data = [],
    ) {
        if (trim($to) === '') {
            throw NotificationException::emptyRecipient();
        }
        if (trim($body) === '') {
            throw NotificationException::emptyBody();
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function make(string $to, string $body, string $title = '', array $data = []): self
    {
        return new self($to, $body, $title, $data);
    }
}
