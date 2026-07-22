<?php

declare(strict_types=1);

namespace Serixa\Charts\Legend;

/**
 * Free-form annotation overlay (value + label) for cartesian charts.
 */
final class Annotation
{
    private function __construct(
        private readonly float $value,
        private readonly string $label = '',
    ) {
    }

    public static function make(float $value, string $label = ''): self
    {
        return new self($value, $label);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function label(): string
    {
        return $this->label;
    }

    /**
     * @return array{value: float, label: string}
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label,
        ];
    }
}
