<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class BooleanRule implements Rule
{
    private const ACCEPTED = [true, false, 0, 1, '0', '1', 'true', 'false', '', null];

    public function passes(string $attribute, mixed $value, array $data): bool
    {
        return in_array($value, self::ACCEPTED, true);
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field must be true or false.";
    }
}
