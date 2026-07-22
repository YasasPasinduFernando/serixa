<?php

declare(strict_types=1);

use Serixa\Forms\State\FormState;

if (! function_exists('old')) {
    function old(string $key, mixed $default = null): mixed
    {
        $state = FormState::current();

        return $state?->value($key, $default) ?? $default;
    }
}

if (! function_exists('errors')) {
    /**
     * @return list<string>
     */
    function errors(string $key): array
    {
        $state = FormState::current();

        return $state?->fieldErrors($key) ?? [];
    }
}

if (! function_exists('hasError')) {
    function hasError(string $key): bool
    {
        $state = FormState::current();

        return $state?->hasError($key) ?? false;
    }
}

if (! function_exists('firstError')) {
    function firstError(string $key): ?string
    {
        $state = FormState::current();

        return $state?->firstError($key);
    }
}
