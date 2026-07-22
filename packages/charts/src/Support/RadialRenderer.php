<?php

declare(strict_types=1);

namespace Serixa\Charts\Support;

use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Theme\ChartsTheme;

/**
 * Shared SVG helpers for radial (pie/donut/radar/polar/gauge/ring) charts.
 *
 * @internal
 */
final class RadialRenderer
{
    /**
     * @return array{cx: float, cy: float, radius: float}
     */
    public static function center(int $width, int $height, float $margin = 24.0): array
    {
        $cx = $width / 2;
        $cy = $height / 2;
        $radius = max(8.0, min($cx, $cy) - $margin);

        return ['cx' => $cx, 'cy' => $cy, 'radius' => $radius];
    }

    /**
     * Sum absolute values of the first series (or flatten all) for pie-like charts.
     *
     * @return list<array{label: string, value: float, color: string, index: int}>
     */
    public static function slices(Dataset $dataset): array
    {
        $labels = $dataset->labelList();
        $seriesList = $dataset->seriesList();

        if ($seriesList === []) {
            return [];
        }

        // Prefer first series values paired with labels; if multiple series with one value each, treat as slices.
        if (count($seriesList) > 1 && count($seriesList[0]->numericValues()) <= 1) {
            $slices = [];

            foreach ($seriesList as $i => $series) {
                $values = $series->numericValues();
                $value = abs($values[0] ?? 0.0);
                $slices[] = [
                    'label' => $series->name() !== '' ? $series->name() : ($labels[$i] ?? (string) $i),
                    'value' => $value,
                    'color' => Scales::colorAt($i, $series->colorValue()),
                    'index' => $i,
                ];
            }

            return $slices;
        }

        $series = $seriesList[0];
        $values = $series->numericValues();
        $slices = [];

        foreach ($values as $i => $value) {
            $slices[] = [
                'label' => $labels[$i] ?? $series->name() . ' ' . ($i + 1),
                'value' => abs($value),
                'color' => Scales::colorAt($i, $series->colorValue()),
                'index' => $i,
            ];
        }

        return $slices;
    }

    /**
     * @param list<array{label: string, value: float, color: string, index: int}> $slices
     */
    public static function renderPiePaths(
        array $slices,
        float $cx,
        float $cy,
        float $radius,
        float $innerRadius,
        ChartsTheme $theme,
        float $startOffset = -M_PI_2,
    ): string {
        $total = 0.0;

        foreach ($slices as $slice) {
            $total += $slice['value'];
        }

        if ($total <= 0.0) {
            return Svg::void('circle', [
                'class' => implode(' ', $theme->classes('charts.ring', ['track' => true])),
                'cx' => $cx,
                'cy' => $cy,
                'r' => $radius,
                'fill' => 'none',
                'stroke' => '#e2e8f0',
                'stroke-width' => 8,
            ]);
        }

        $angle = $startOffset;
        $html = '';

        foreach ($slices as $slice) {
            $sweep = ($slice['value'] / $total) * 2 * M_PI;
            $end = $angle + $sweep;
            $class = implode(' ', $theme->classes('charts.slice', ['index' => $slice['index']]));

            // Full circle special-case (SVG arc of 360° is degenerate).
            if ($sweep >= 2 * M_PI - 0.0001) {
                if ($innerRadius > 0) {
                    $html .= Svg::void('circle', [
                        'class' => $class,
                        'cx' => $cx,
                        'cy' => $cy,
                        'r' => ($radius + $innerRadius) / 2,
                        'fill' => 'none',
                        'stroke' => $slice['color'],
                        'stroke-width' => max(1.0, $radius - $innerRadius),
                        'data-series-index' => (string) $slice['index'],
                        'data-label' => $slice['label'],
                        'data-value' => (string) $slice['value'],
                        'tabindex' => '0',
                        'role' => 'img',
                        'aria-label' => $slice['label'] . ': ' . CartesianRenderer::formatTick($slice['value']),
                    ]);
                } else {
                    $html .= Svg::void('circle', [
                        'class' => $class,
                        'cx' => $cx,
                        'cy' => $cy,
                        'r' => $radius,
                        'fill' => $slice['color'],
                        'data-series-index' => (string) $slice['index'],
                        'data-label' => $slice['label'],
                        'data-value' => (string) $slice['value'],
                        'tabindex' => '0',
                        'role' => 'img',
                        'aria-label' => $slice['label'] . ': ' . CartesianRenderer::formatTick($slice['value']),
                    ]);
                }
            } else {
                $html .= Svg::void('path', [
                    'class' => $class,
                    'd' => Svg::arcPath($cx, $cy, $radius, $angle, $end, $innerRadius),
                    'fill' => $slice['color'],
                    'data-series-index' => (string) $slice['index'],
                    'data-label' => $slice['label'],
                    'data-value' => (string) $slice['value'],
                    'tabindex' => '0',
                    'role' => 'img',
                    'aria-label' => $slice['label'] . ': ' . CartesianRenderer::formatTick($slice['value']),
                ]);
            }

            $angle = $end;
        }

        return $html;
    }

    /**
     * Progress / gauge arc from min→max mapped to start→end angles.
     *
     * @return array{start: float, end: float, fullStart: float, fullEnd: float, ratio: float}
     */
    public static function arcAngles(float $value, float $min, float $max, float $start = -M_PI * 0.75, float $end = M_PI * 0.75): array
    {
        $t = 0.0;

        if ($max != $min) {
            $t = max(0.0, min(1.0, ($value - $min) / ($max - $min)));
        }

        return [
            'start' => $start,
            'end' => $start + ($end - $start) * $t,
            'fullStart' => $start,
            'fullEnd' => $end,
            'ratio' => $t,
        ];
    }

    public static function centeredLabel(float $cx, float $cy, string $text, ChartsTheme $theme, int $fontSize = 18): string
    {
        $class = implode(' ', $theme->classes('charts.title'));

        return Svg::tag('text', [
            'class' => $class,
            'x' => $cx,
            'y' => $cy,
            'text-anchor' => 'middle',
            'dominant-baseline' => 'middle',
            'font-size' => $fontSize,
            'fill' => 'currentColor',
        ], Svg::escape($text));
    }
}
