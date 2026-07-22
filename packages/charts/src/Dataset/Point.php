<?php

declare(strict_types=1);

namespace Serixa\Charts\Dataset;

/**
 * Cartesian / bubble point used by scatter and bubble charts.
 */
final class Point implements Arrayable
{
    private function __construct(
        private readonly float $x,
        private readonly float $y,
        private readonly ?float $r = null,
    ) {
    }

    public static function make(float $x, float $y, ?float $r = null): self
    {
        return new self($x, $y, $r);
    }

    public function x(): float
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }

    public function r(): ?float
    {
        return $this->r;
    }

    /**
     * @return array{x: float, y: float, r?: float}
     */
    public function toArray(): array
    {
        $out = ['x' => $this->x, 'y' => $this->y];

        if ($this->r !== null) {
            $out['r'] = $this->r;
        }

        return $out;
    }
}
