<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class ConfirmedRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        $confirmation = $data[$attribute . '_confirmation'] ?? null;

        return $value === $confirmation;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The {$field} field confirmation does not match.";
    }
}
