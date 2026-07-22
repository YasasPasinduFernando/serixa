<?php

declare(strict_types=1);

namespace Serixa\WebAuthn;

use Serixa\Passkeys\PasskeyChallenge;
use Serixa\Passkeys\PasskeyCredential;

/**
 * Builds PublicKeyCredential creation/request option arrays for the browser.
 */
final class WebAuthnOptions
{
    public function __construct(
        private readonly string $rpId,
        private readonly string $rpName,
        private readonly string $origin,
    ) {
    }

    public static function make(string $rpId, string $rpName, string $origin): self
    {
        return new self($rpId, $rpName, $origin);
    }

    /**
     * @return array<string, mixed>
     */
    public function registrationOptions(PasskeyChallenge $challenge, string $userId, string $userName, string $userDisplayName): array
    {
        return [
            'challenge' => $challenge->challenge(),
            'rp' => ['id' => $this->rpId, 'name' => $this->rpName],
            'user' => [
                'id' => Base64Url::encode($userId),
                'name' => $userName,
                'displayName' => $userDisplayName,
            ],
            'pubKeyCredParams' => [
                ['type' => 'public-key', 'alg' => -7],
                ['type' => 'public-key', 'alg' => -257],
            ],
            'timeout' => 60000,
            'attestation' => 'none',
            'authenticatorSelection' => [
                'residentKey' => 'preferred',
                'userVerification' => 'preferred',
            ],
        ];
    }

    /**
     * @param list<PasskeyCredential> $allowCredentials
     *
     * @return array<string, mixed>
     */
    public function authenticationOptions(PasskeyChallenge $challenge, array $allowCredentials = []): array
    {
        $allow = [];
        foreach ($allowCredentials as $credential) {
            $allow[] = [
                'type' => 'public-key',
                'id' => $credential->id(),
            ];
        }

        return [
            'challenge' => $challenge->challenge(),
            'timeout' => 60000,
            'rpId' => $this->rpId,
            'userVerification' => 'preferred',
            'allowCredentials' => $allow,
        ];
    }

    public function origin(): string
    {
        return $this->origin;
    }

    public function rpId(): string
    {
        return $this->rpId;
    }
}
