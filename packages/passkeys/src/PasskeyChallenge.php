<?php

declare(strict_types=1);

namespace Serixa\Passkeys;

use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;

final class PasskeyChallenge
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $challenge,
        private readonly string $purpose = 'authentication',
        private readonly ?DateTimeImmutable $expiresAt = null,
    ) {
        if ($id === '' || $userId === '' || $challenge === '') {
            throw new InvalidArgumentException('Challenge id, userId, and challenge are required.');
        }
    }

    public static function make(
        string $id,
        string $userId,
        string $challenge,
        string $purpose = 'authentication',
        ?DateTimeImmutable $expiresAt = null,
    ): self {
        return new self($id, $userId, $challenge, $purpose, $expiresAt);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function challenge(): string
    {
        return $this->challenge;
    }

    public function purpose(): string
    {
        return $this->purpose;
    }

    public function expiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(?DateTimeImmutable $now = null): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        return ($now ?? new DateTimeImmutable()) > $this->expiresAt;
    }
}
