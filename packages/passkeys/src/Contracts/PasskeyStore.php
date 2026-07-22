<?php

declare(strict_types=1);

namespace Serixa\Passkeys\Contracts;

use Serixa\Passkeys\PasskeyChallenge;
use Serixa\Passkeys\PasskeyCredential;

interface PasskeyStore
{
    public function putCredential(PasskeyCredential $credential): void;

    public function getCredential(string $id): ?PasskeyCredential;

    /**
     * @return list<PasskeyCredential>
     */
    public function credentialsFor(string $userId): array;

    public function removeCredential(string $id): void;

    public function putChallenge(PasskeyChallenge $challenge): void;

    public function getChallenge(string $id): ?PasskeyChallenge;

    public function removeChallenge(string $id): void;
}
