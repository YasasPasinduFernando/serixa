<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class DifferentRule implements Rule
{
    public function __construct(
        private readonly string $other,
    ) {
    }

    public function passes(string $attribute, mixed $value, array $data): bool
    {
        return ! array_key_exists($this->other, $data) || $value !== $data[$this->other];
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);
        $other = str_replace('_', ' ', $this->other);

        return "The {$field} field must be different from {$other}.";
    }
}
