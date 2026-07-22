<?php

declare(strict_types=1);

namespace Serixa\Charts\Support;

/**
 * Linear scale helpers for mapping domain values to SVG coordinates.
 *
 * @internal
 */
final class Scales
{
    private function __construct()
    {
    }

    /**
     * Map a domain value into a range (linear).
     */
    public static function linear(float $value, float $domainMin, float $domainMax, float $rangeMin, float $rangeMax): float
    {
        if ($domainMax == $domainMin) {
            return ($rangeMin + $rangeMax) / 2;
        }

        $t = ($value - $domainMin) / ($domainMax - $domainMin);

        return $rangeMin + $t * ($rangeMax - $rangeMin);
    }

    /**
     * Invert Y so domain max maps near the top of the SVG plot.
     */
    public static function linearY(float $value, float $domainMin, float $domainMax, float $plotTop, float $plotBottom): float
    {
        return self::linear($value, $domainMin, $domainMax, $plotBottom, $plotTop);
    }

    /**
     * Expand domain by a small pad; ensure non-zero span.
     *
     * @return array{0: float, 1: float}
     */
    public static function niceDomain(float $min, float $max, float $padRatio = 0.05): array
    {
        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        if ($min === $max) {
            if ($min === 0.0) {
                return [0.0, 1.0];
            }

            $pad = abs($min) * $padRatio;

            return [$min - $pad, $max + $pad];
        }

        $span = $max - $min;
        $pad = $span * $padRatio;

        return [$min - $pad, $max + $pad];
    }

    /**
     * Evenly spaced tick values across a domain.
     *
     * @return list<float>
     */
    public static function ticks(float $min, float $max, int $count = 5): array
    {
        if ($count < 2) {
            return [$min];
        }

        if ($min === $max) {
            return [$min];
        }

        $out = [];

        for ($i = 0; $i < $count; $i++) {
            $t = $i / ($count - 1);
            $out[] = $min + ($max - $min) * $t;
        }

        return $out;
    }

    /**
     * Default teal/slate series palette (CSS-friendly hex).
     *
     * @return list<string>
     */
    public static function defaultPalette(): array
    {
        return [
            '#0d9488',
            '#475569',
            '#0891b2',
            '#64748b',
            '#14b8a6',
            '#334155',
            '#06b6d4',
            '#78716c',
        ];
    }

    public static function colorAt(int $index, ?string $override = null): string
    {
        if ($override !== null && $override !== '') {
            return $override;
        }

        $palette = self::defaultPalette();

        return $palette[$index % count($palette)];
    }
}
