<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation;

final class ValidationError
{
    private function __construct(
        private readonly string $attribute,
        private readonly string $message,
        private readonly ?string $rule = null,
    ) {
    }

    public static function make(string $attribute, string $message, ?string $rule = null): self
    {
        return new self($attribute, $message, $rule);
    }

    public function attribute(): string
    {
        return $this->attribute;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function rule(): ?string
    {
        return $this->rule;
    }
}
