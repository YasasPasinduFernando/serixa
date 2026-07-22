<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class RegexRule implements Rule
{
    public function __construct(
        private readonly string $pattern,
    ) {
    }

    public function passes(string $attribute, mixed $value, array $data): bool
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match($this->pattern, (string) $value) === 1;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field format is invalid.";
    }
}
