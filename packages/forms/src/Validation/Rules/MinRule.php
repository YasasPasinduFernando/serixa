<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class MinRule implements Rule
{
    public function __construct(
        private readonly int|float $min,
    ) {
    }

    public function passes(string $attribute, mixed $value, array $data): bool
    {
        $size = $this->size($value);

        if ($size === null) {
            return false;
        }

        return $size >= $this->min;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        if (is_string($value)) {
            return "The {$field} field must be at least {$this->min} characters.";
        }

        if (is_array($value)) {
            return "The {$field} field must have at least {$this->min} items.";
        }

        return "The {$field} field must be at least {$this->min}.";
    }

    private function size(mixed $value): int|float|null
    {
        if (is_array($value)) {
            return count($value);
        }

        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (is_string($value)) {
            return mb_strlen($value);
        }

        return null;
    }
}
