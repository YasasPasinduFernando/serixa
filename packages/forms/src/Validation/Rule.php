<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation;

interface Rule
{
    /**
     * @param array<string, mixed> $data
     */
    public function passes(string $attribute, mixed $value, array $data): bool;

    /**
     * @param array<string, mixed> $data
     */
    public function message(string $attribute, mixed $value, array $data): string;
}
