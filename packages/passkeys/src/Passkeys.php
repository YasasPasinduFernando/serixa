<?php

declare(strict_types=1);

namespace Serixa\Passkeys;

use DateInterval;
use DateTimeImmutable;
use Serixa\Passkeys\Contracts\PasskeyStore;
use Serixa\Passkeys\Exceptions\PasskeyException;
use Serixa\Passkeys\Memory\MemoryPasskeyStore;

/**
 * High-level passkey registration/authentication challenge management.
 * Cryptographic verification is left to WebAuthn adapters in the host app.
 */
final class Passkeys
{
    private int $seq = 0;

    public function __construct(private readonly PasskeyStore $store)
    {
    }

    public static function memory(): self
    {
        return new self(new MemoryPasskeyStore());
    }

    public function store(): PasskeyStore
    {
        return $this->store;
    }

    public function beginRegistration(string $userId, int $ttlSeconds = 300): PasskeyChallenge
    {
        return $this->createChallenge($userId, 'registration', $ttlSeconds);
    }

    public function beginAuthentication(string $userId, int $ttlSeconds = 300): PasskeyChallenge
    {
        return $this->createChallenge($userId, 'authentication', $ttlSeconds);
    }

    public function register(
        string $credentialId,
        string $userId,
        string $publicKey,
        string $challengeId,
    ): PasskeyCredential {
        $this->consumeChallenge($challengeId, $userId, 'registration');
        $credential = PasskeyCredential::make($credentialId, $userId, $publicKey);
        $this->store->putCredential($credential);

        return $credential;
    }

    public function assertChallenge(string $challengeId, string $userId, string $purpose = 'authentication'): PasskeyChallenge
    {
        return $this->consumeChallenge($challengeId, $userId, $purpose);
    }

    /**
     * @return list<PasskeyCredential>
     */
    public function credentialsFor(string $userId): array
    {
        return $this->store->credentialsFor($userId);
    }

    private function createChallenge(string $userId, string $purpose, int $ttlSeconds): PasskeyChallenge
    {
        ++$this->seq;
        $challenge = PasskeyChallenge::make(
            'chg_' . $this->seq,
            $userId,
            rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='),
            $purpose,
            (new DateTimeImmutable())->add(new DateInterval('PT' . max(1, $ttlSeconds) . 'S')),
        );
        $this->store->putChallenge($challenge);

        return $challenge;
    }

    private function consumeChallenge(string $challengeId, string $userId, string $purpose): PasskeyChallenge
    {
        $challenge = $this->store->getChallenge($challengeId);
        if ($challenge === null) {
            throw PasskeyException::challengeNotFound($challengeId);
        }
        if ($challenge->isExpired() || $challenge->userId() !== $userId || $challenge->purpose() !== $purpose) {
            throw PasskeyException::challengeExpired($challengeId);
        }
        $this->store->removeChallenge($challengeId);

        return $challenge;
    }
}
