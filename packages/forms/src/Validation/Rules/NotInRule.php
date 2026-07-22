<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation\Rules;

use Serixa\Forms\Validation\Rule;

final class NotInRule implements Rule
{
    /**
     * @param list<mixed> $values
     */
    public function __construct(
        private readonly array $values,
    ) {
    }

    public function passes(string $attribute, mixed $value, array $data): bool
    {
        return ! in_array($value, $this->values, true);
    }

    public function message(string $attribute, mixed $value, array $data): string
    {
        $field = str_replace('_', ' ', $attribute);

        return "The selected {$field} is invalid.";
    }
}
