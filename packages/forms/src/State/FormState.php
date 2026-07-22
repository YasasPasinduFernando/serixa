<?php

declare(strict_types=1);

namespace Serixa\Forms\State;

final class FormState
{
    private static ?self $current = null;

    private ErrorsBag $errors;

    private OldInput $oldInput;

    /** @var array<string, mixed> */
    private array $defaults = [];

    /** @var array<string, mixed> */
    private array $values = [];

    private function __construct()
    {
        $this->errors = ErrorsBag::make();
        $this->oldInput = OldInput::make();
    }

    public static function make(): self
    {
        return new self();
    }

    public function withErrors(ErrorsBag $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function withOldInput(OldInput $old): self
    {
        $this->oldInput = $old;

        return $this;
    }

    /**
     * @param array<string, mixed> $defaults
     */
    public function withDefaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @param array<string, mixed> $values
     */
    public function withValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function errors(): ErrorsBag
    {
        return $this->errors;
    }

    public function oldInput(): OldInput
    {
        return $this->oldInput;
    }

    /**
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return $this->defaults;
    }

    /**
     * @return array<string, mixed>
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * Resolve a field value: old input > bound values > defaults > $default.
     */
    public function value(string $key, mixed $default = null): mixed
    {
        if ($this->oldInput->has($key)) {
            return $this->oldInput->get($key);
        }

        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        if (array_key_exists($key, $this->defaults)) {
            return $this->defaults[$key];
        }

        return $default;
    }

    public function hasError(string $key): bool
    {
        return $this->errors->has($key);
    }

    public function firstError(string $key): ?string
    {
        return $this->errors->first($key);
    }

    /**
     * @return list<string>
     */
    public function fieldErrors(string $key): array
    {
        return $this->errors->get($key);
    }

    public function setCurrent(): void
    {
        self::$current = $this;
    }

    public static function current(): ?self
    {
        return self::$current;
    }

    public static function clearCurrent(): void
    {
        self::$current = null;
    }
}
