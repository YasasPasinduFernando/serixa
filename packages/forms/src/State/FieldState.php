<?php

declare(strict_types=1);

namespace Serixa\Forms\State;

final class FieldState
{
    private string $name;

    private mixed $value = null;

    /** @var list<string> */
    private array $errorMessages = [];

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setValue(mixed $value): self
    {
        return $this->value($value);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Set field errors, or return current errors when called with no arguments.
     *
     * @param list<string>|null $errors
     * @return ($errors is null ? list<string> : self)
     */
    public function errors(?array $errors = null): self|array
    {
        if ($errors === null) {
            return $this->errorMessages;
        }

        $this->errorMessages = array_values($errors);

        return $this;
    }

    public function addError(string $message): self
    {
        $this->errorMessages[] = $message;

        return $this;
    }

    public function hasError(): bool
    {
        return $this->errorMessages !== [];
    }

    public function firstError(): ?string
    {
        return $this->errorMessages[0] ?? null;
    }

    public function invalid(): bool
    {
        return $this->hasError();
    }
}
