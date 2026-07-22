<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class NumericRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        if (is_int($value) || is_float($value)) {
            return true;
        }

        if (! is_string($value)) {
            return false;
        }

        return is_numeric($value);
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field must be a number.";
    }
}
