<?php

declare(strict_types=1);

namespace Serixa\Passkeys;

use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;

final class PasskeyCredential
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $publicKey,
        private readonly string $signCount = '0',
        private readonly ?DateTimeImmutable $createdAt = null,
    ) {
        if ($id === '' || $userId === '' || $publicKey === '') {
            throw new InvalidArgumentException('Passkey id, userId, and publicKey are required.');
        }
    }

    public static function make(
        string $id,
        string $userId,
        string $publicKey,
        string $signCount = '0',
        ?DateTimeImmutable $createdAt = null,
    ): self {
        return new self($id, $userId, $publicKey, $signCount, $createdAt);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function publicKey(): string
    {
        return $this->publicKey;
    }

    public function signCount(): string
    {
        return $this->signCount;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt ?? new DateTimeImmutable();
    }

    public function withSignCount(string $signCount): self
    {
        return new self($this->id, $this->userId, $this->publicKey, $signCount, $this->createdAt);
    }
}
