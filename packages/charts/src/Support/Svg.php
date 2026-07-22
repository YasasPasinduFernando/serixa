<?php

declare(strict_types=1);

namespace Serixa\Charts\Support;

/**
 * SVG markup helpers for SSR chart rendering.
 *
 * @internal
 */
final class Svg
{
    private function __construct()
    {
    }

    public static function escape(string $value): string
    {
        return Html::escape($value);
    }

    /**
     * @param array<string, string|float|int|bool|null> $attributes
     */
    public static function tag(string $tag, array $attributes = [], string $inner = ''): string
    {
        return Html::tag($tag, self::attrs($attributes), $inner);
    }

    /**
     * @param array<string, string|float|int|bool|null> $attributes
     */
    public static function void(string $tag, array $attributes = []): string
    {
        return Html::voidTag($tag, self::attrs($attributes));
    }

    /**
     * Build an SVG path `d` attribute from absolute move/line commands.
     *
     * @param list<array{0: float, 1: float}> $points
     */
    public static function linePath(array $points, bool $closed = false): string
    {
        if ($points === []) {
            return '';
        }

        $parts = [];

        foreach ($points as $i => [$x, $y]) {
            $cmd = $i === 0 ? 'M' : 'L';
            $parts[] = sprintf('%s%.2f %.2f', $cmd, $x, $y);
        }

        if ($closed) {
            $parts[] = 'Z';
        }

        return implode(' ', $parts);
    }

    /**
     * Catmull-Rom inspired smooth path through points (cubic beziers).
     *
     * @param list<array{0: float, 1: float}> $points
     */
    public static function smoothPath(array $points, bool $closed = false): string
    {
        $count = count($points);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return sprintf('M%.2f %.2f', $points[0][0], $points[0][1]);
        }

        if ($count === 2) {
            return self::linePath($points, $closed);
        }

        $d = sprintf('M%.2f %.2f', $points[0][0], $points[0][1]);

        for ($i = 0; $i < $count - 1; $i++) {
            $p0 = $points[max(0, $i - 1)];
            $p1 = $points[$i];
            $p2 = $points[$i + 1];
            $p3 = $points[min($count - 1, $i + 2)];

            $cp1x = $p1[0] + ($p2[0] - $p0[0]) / 6;
            $cp1y = $p1[1] + ($p2[1] - $p0[1]) / 6;
            $cp2x = $p2[0] - ($p3[0] - $p1[0]) / 6;
            $cp2y = $p2[1] - ($p3[1] - $p1[1]) / 6;

            $d .= sprintf(
                ' C%.2f %.2f, %.2f %.2f, %.2f %.2f',
                $cp1x,
                $cp1y,
                $cp2x,
                $cp2y,
                $p2[0],
                $p2[1],
            );
        }

        if ($closed) {
            $d .= ' Z';
        }

        return $d;
    }

    /**
     * Arc path for pie/donut slices (angles in radians, 0 = east).
     */
    public static function arcPath(
        float $cx,
        float $cy,
        float $radius,
        float $startAngle,
        float $endAngle,
        float $innerRadius = 0.0,
    ): string {
        $large = ($endAngle - $startAngle) > M_PI ? 1 : 0;

        $x1 = $cx + $radius * cos($startAngle);
        $y1 = $cy + $radius * sin($startAngle);
        $x2 = $cx + $radius * cos($endAngle);
        $y2 = $cy + $radius * sin($endAngle);

        if ($innerRadius <= 0.0) {
            return sprintf(
                'M%.2f %.2f L%.2f %.2f A%.2f %.2f 0 %d 1 %.2f %.2f Z',
                $cx,
                $cy,
                $x1,
                $y1,
                $radius,
                $radius,
                $large,
                $x2,
                $y2,
            );
        }

        $ix1 = $cx + $innerRadius * cos($startAngle);
        $iy1 = $cy + $innerRadius * sin($startAngle);
        $ix2 = $cx + $innerRadius * cos($endAngle);
        $iy2 = $cy + $innerRadius * sin($endAngle);

        return sprintf(
            'M%.2f %.2f A%.2f %.2f 0 %d 1 %.2f %.2f L%.2f %.2f A%.2f %.2f 0 %d 0 %.2f %.2f Z',
            $x1,
            $y1,
            $radius,
            $radius,
            $large,
            $x2,
            $y2,
            $ix2,
            $iy2,
            $innerRadius,
            $innerRadius,
            $large,
            $ix1,
            $iy1,
        );
    }

    /**
     * @param array<string, string|float|int|bool|null> $attributes
     */
    private static function attrs(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $parts[] = ' ' . $name;

                continue;
            }

            if (is_float($value)) {
                $value = rtrim(rtrim(sprintf('%.4f', $value), '0'), '.');
            }

            $parts[] = sprintf(' %s="%s"', $name, self::escape((string) $value));
        }

        return implode('', $parts);
    }
}
