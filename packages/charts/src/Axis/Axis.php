<?php

declare(strict_types=1);

namespace Serixa\Charts\Axis;

/**
 * Shared axis options for cartesian charts.
 */
abstract class Axis
{
    protected ?string $label = null;

    protected ?float $min = null;

    protected ?float $max = null;

    /**
     * @return static
     */
    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return static
     */
    public function min(float $min): static
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return static
     */
    public function max(float $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function labelValue(): ?string
    {
        return $this->label;
    }

    public function minValue(): ?float
    {
        return $this->min;
    }

    public function maxValue(): ?float
    {
        return $this->max;
    }

    /**
     * @return array{label: ?string, min: ?float, max: ?float}
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}
