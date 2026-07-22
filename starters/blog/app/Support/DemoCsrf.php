<?php

declare(strict_types=1);

namespace App\Support;

use Serixa\Forms\Contracts\CsrfProvider;

/**
 * Demo-only CSRF provider for starter validation UIs.
 * Not for production — replace with a real session-backed implementation.
 */
final class DemoCsrf implements CsrfProvider
{
    public function token(): string
    {
        return 'serixa-demo-csrf-token';
    }

    public function fieldName(): string
    {
        return '_token';
    }

    public function validate(?string $token): bool
    {
        return $token === $this->token();
    }
}
