<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class IntegerRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        if (is_int($value)) {
            return true;
        }

        if (is_string($value) && preg_match('/^-?\d+$/', $value) === 1) {
            return true;
        }

        return false;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field must be an integer.";
    }
}
