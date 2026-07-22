<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class RequiredRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value) && $value === '') {
            return false;
        }

        if (is_array($value) && $value === []) {
            return false;
        }

        return true;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field is required.";
    }
}
