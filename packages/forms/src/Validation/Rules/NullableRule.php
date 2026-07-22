<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class NullableRule implements Rule
{
    public function passes(string $attribute, mixed $value, array $data): bool
    {
        return true;
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        return '';
    }
}
