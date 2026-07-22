<?php

declare(strict_types=1);

namespace Serixa\Charts\Dataset;

/**
 * Named series of numeric values (or points) for a chart.
 */
final class Series implements Arrayable
{
    private string $name;

    /** @var list<float|Point> */
    private array $values = [];

    private ?string $color = null;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    /**
     * @param iterable<mixed> $values
     */
    public function values(iterable $values): self
    {
        $list = [];

        foreach ($values as $value) {
            if ($value instanceof Point) {
                $list[] = $value;

                continue;
            }

            if (is_array($value) && isset($value['x'], $value['y'])) {
                $list[] = Point::make(
                    (float) $value['x'],
                    (float) $value['y'],
                    isset($value['r']) ? (float) $value['r'] : null,
                );

                continue;
            }

            if (is_int($value) || is_float($value) || (is_string($value) && is_numeric($value))) {
                $list[] = (float) $value;

                continue;
            }

            $list[] = Normalizer::numbers([$value])[0] ?? 0.0;
        }

        $this->values = $list;

        return $this;
    }

    public function color(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return list<float|Point>
     */
    public function rawValues(): array
    {
        return $this->values;
    }

    /**
     * Numeric Y (or scalar) values only — points contribute their Y.
     *
     * @return list<float>
     */
    public function numericValues(): array
    {
        $out = [];

        foreach ($this->values as $value) {
            $out[] = $value instanceof Point ? $value->y() : $value;
        }

        return $out;
    }

    public function colorValue(): ?string
    {
        return $this->color;
    }

    /**
     * @return array{name: string, values: list<float|array{x: float, y: float, r?: float}>, color: ?string}
     */
    public function toArray(): array
    {
        $values = [];

        foreach ($this->values as $value) {
            $values[] = $value instanceof Point ? $value->toArray() : $value;
        }

        return [
            'name' => $this->name,
            'values' => $values,
            'color' => $this->color,
        ];
    }
}
