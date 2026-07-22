<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class UrlRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        if (! is_string($value)) {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field must be a valid URL.";
    }
}
