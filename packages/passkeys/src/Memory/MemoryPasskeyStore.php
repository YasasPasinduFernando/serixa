<?php

declare(strict_types=1);

namespace Serixa\Passkeys\Memory;

use Serixa\Passkeys\Contracts\PasskeyStore;
use Serixa\Passkeys\PasskeyChallenge;
use Serixa\Passkeys\PasskeyCredential;

final class MemoryPasskeyStore implements PasskeyStore
{
    /** @var array<string, PasskeyCredential> */
    private array $credentials = [];

    /** @var array<string, PasskeyChallenge> */
    private array $challenges = [];

    public function putCredential(PasskeyCredential $credential): void
    {
        $this->credentials[$credential->id()] = $credential;
    }

    public function getCredential(string $id): ?PasskeyCredential
    {
        return $this->credentials[$id] ?? null;
    }

    public function credentialsFor(string $userId): array
    {
        $out = [];
        foreach ($this->credentials as $credential) {
            if ($credential->userId() === $userId) {
                $out[] = $credential;
            }
        }

        return $out;
    }

    public function removeCredential(string $id): void
    {
        unset($this->credentials[$id]);
    }

    public function putChallenge(PasskeyChallenge $challenge): void
    {
        $this->challenges[$challenge->id()] = $challenge;
    }

    public function getChallenge(string $id): ?PasskeyChallenge
    {
        return $this->challenges[$id] ?? null;
    }

    public function removeChallenge(string $id): void
    {
        unset($this->challenges[$id]);
    }
}
