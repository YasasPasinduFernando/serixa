<?php

declare(strict_types=1);

namespace Serixa\Component\Concerns;

/**
 * Shared form-control attributes (name, id, disabled, required, value).
 *
 * @internal
 */
trait HasFormControl
{
    private ?string $name = null;

    private ?string $value = null;

    private bool $disabled = false;

    private bool $required = false;

    private bool $invalid = false;

    private ?string $describedBy = null;

    /**
     * @return static
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return static
     */
    public function value(string|int|float|null $value): static
    {
        $this->value = $value === null ? null : (string) $value;

        return $this;
    }

    /**
     * @return static
     */
    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return static
     */
    public function required(bool $required = true): static
    {
        $this->required = $required;

        return $this;
    }

    /**
     * UI-only invalid flag (no validation engine). Sets aria-invalid.
     *
     * @return static
     */
    public function invalid(bool $invalid = true): static
    {
        $this->invalid = $invalid;

        return $this;
    }

    /**
     * Space-separated element ids for aria-describedby (helper / error text).
     *
     * @return static
     */
    public function describedBy(string $ids): static
    {
        $this->describedBy = $ids;

        return $this;
    }

    /**
     * @return array<string, string|bool|null>
     */
    protected function formControlAttributes(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'disabled' => $this->disabled ? true : null,
            'required' => $this->required ? true : null,
            'aria-invalid' => $this->invalid ? 'true' : null,
            'aria-disabled' => $this->disabled ? 'true' : null,
            'aria-required' => $this->required ? 'true' : null,
            'aria-describedby' => $this->describedBy,
        ];
    }
}
