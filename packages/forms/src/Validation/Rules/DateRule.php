<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use DateTime;
use DateTimeInterface;
use Serixa\Forms\Validation\Rule;

final class DateRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if (! is_string($value) && ! is_int($value)) {
            return false;
        }

        if (is_string($value) && $value === '') {
            return false;
        }

        if (@strtotime((string) $value) !== false) {
            return true;
        }

        try {
            new DateTime((string) $value);

            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field must be a valid date.";
    }
}
