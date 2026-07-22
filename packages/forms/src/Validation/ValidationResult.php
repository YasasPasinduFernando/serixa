<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation;

use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\FormState;

final class ValidationResult
{
    /** @var list<ValidationError> */
    private readonly array $errors;

    /**
     * @param list<ValidationError> $errors
     */
    private function __construct(
        private readonly bool $ok,
        array $errors = [],
    ) {
        $this->errors = array_values($errors);
    }

    public static function pass(): self
    {
        return new self(true, []);
    }

    public static function fail(ValidationError ...$errors): self
    {
        return new self(false, array_values($errors));
    }

    public function ok(): bool
    {
        return $this->ok;
    }

    public function failed(): bool
    {
        return ! $this->ok;
    }

    public function passed(): bool
    {
        return $this->ok;
    }

    /**
     * @return list<ValidationError>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function toErrorsBag(): ErrorsBag
    {
        /** @var array<string, list<string>> $messages */
        $messages = [];

        foreach ($this->errors as $error) {
            $attribute = $error->attribute();

            if (! isset($messages[$attribute])) {
                $messages[$attribute] = [];
            }

            $messages[$attribute][] = $error->message();
        }

        return ErrorsBag::make($messages);
    }

    public function toFormState(): FormState
    {
        return FormState::make()->withErrors($this->toErrorsBag());
    }
}
