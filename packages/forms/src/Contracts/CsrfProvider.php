<?php

declare(strict_types=1);

namespace Serixa\Forms\Contracts;

interface CsrfProvider
{
    public function token(): string;

    /**
     * Suggested default field name: "_token".
     */
    public function fieldName(): string;

    public function validate(?string $token): bool;
}
