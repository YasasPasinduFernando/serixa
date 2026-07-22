<?php

declare(strict_types=1);

namespace Serixa\Charts\Support;

use Serixa\Charts\Axis\Grid;
use Serixa\Charts\Axis\XAxis;
use Serixa\Charts\Axis\YAxis;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Point;
use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Legend\Annotation;
use Serixa\Charts\Legend\Marker;
use Serixa\Charts\Legend\Threshold;
use Serixa\Charts\Theme\ChartsTheme;

/**
 * Shared SVG helpers for cartesian (line/bar/area/scatter/bubble/sparkline) charts.
 *
 * @internal
 */
final class CartesianRenderer
{
    /**
     * @return array{left: float, top: float, right: float, bottom: float, width: float, height: float}
     */
    public static function plotBox(int $width, int $height, int $padLeft = 48, int $padRight = 16, int $padTop = 16, int $padBottom = 40): array
    {
        $left = (float) $padLeft;
        $top = (float) $padTop;
        $right = (float) ($width - $padRight);
        $bottom = (float) ($height - $padBottom);

        return [
            'left' => $left,
            'top' => $top,
            'right' => $right,
            'bottom' => $bottom,
            'width' => max(1.0, $right - $left),
            'height' => max(1.0, $bottom - $top),
        ];
    }

    /**
     * @return array{0: float, 1: float}
     */
    public static function yDomain(Dataset $dataset, ?YAxis $yAxis = null): array
    {
        $min = $yAxis?->minValue();
        $max = $yAxis?->maxValue();

        if ($min !== null && $max !== null) {
            return Scales::niceDomain($min, $max, 0.0);
        }

        $values = [];

        foreach ($dataset->seriesList() as $series) {
            foreach ($series->numericValues() as $value) {
                $values[] = $value;
            }
        }

        if ($values === []) {
            return [0.0, 1.0];
        }

        $dataMin = min($values);
        $dataMax = max($values);

        if ($min !== null) {
            $dataMin = $min;
        }

        if ($max !== null) {
            $dataMax = $max;
        }

        // Keep zero in view for bars when all positive/negative.
        if ($dataMin > 0) {
            $dataMin = 0.0;
        }

        if ($dataMax < 0) {
            $dataMax = 0.0;
        }

        return Scales::niceDomain($dataMin, $dataMax);
    }

    /**
     * @return array{0: float, 1: float}
     */
    public static function xDomainFromPoints(Dataset $dataset, ?XAxis $xAxis = null): array
    {
        $min = $xAxis?->minValue();
        $max = $xAxis?->maxValue();

        if ($min !== null && $max !== null) {
            return Scales::niceDomain($min, $max, 0.0);
        }

        $xs = [];

        foreach ($dataset->seriesList() as $series) {
            foreach ($series->rawValues() as $value) {
                if ($value instanceof Point) {
                    $xs[] = $value->x();
                }
            }
        }

        if ($xs === []) {
            $labels = $dataset->labelList();
            $count = max(1, count($labels));

            return [0.0, (float) max(1, $count - 1)];
        }

        $dataMin = min($xs);
        $dataMax = max($xs);

        if ($min !== null) {
            $dataMin = $min;
        }

        if ($max !== null) {
            $dataMax = $max;
        }

        return Scales::niceDomain($dataMin, $dataMax);
    }

    /**
     * @param array{left: float, top: float, right: float, bottom: float, width: float, height: float} $plot
     */
    public static function renderGrid(
        array $plot,
        float $yMin,
        float $yMax,
        ChartsTheme $theme,
        ?Grid $grid = null,
        int $ticks = 5,
    ): string {
        if ($grid !== null && ! $grid->isShown()) {
            return '';
        }

        $class = implode(' ', $theme->classes('charts.grid'));
        $html = '';

        foreach (Scales::ticks($yMin, $yMax, $ticks) as $tick) {
            $y = Scales::linearY($tick, $yMin, $yMax, $plot['top'], $plot['bottom']);
            $html .= Svg::void('line', [
                'class' => $class,
                'x1' => $plot['left'],
                'y1' => $y,
                'x2' => $plot['right'],
                'y2' => $y,
            ]);
        }

        return $html;
    }

    /**
     * @param array{left: float, top: float, right: float, bottom: float, width: float, height: float} $plot
     * @param list<string> $labels
     */
    public static function renderAxes(
        array $plot,
        array $labels,
        float $yMin,
        float $yMax,
        ChartsTheme $theme,
        ?XAxis $xAxis = null,
        ?YAxis $yAxis = null,
        int $yTicks = 5,
    ): string {
        $axisClass = implode(' ', $theme->classes('charts.axis'));
        $html = '';

        // Baseline axes
        $html .= Svg::void('line', [
            'class' => $axisClass,
            'stroke' => 'currentColor',
            'x1' => $plot['left'],
            'y1' => $plot['bottom'],
            'x2' => $plot['right'],
            'y2' => $plot['bottom'],
        ]);
        $html .= Svg::void('line', [
            'class' => $axisClass,
            'stroke' => 'currentColor',
            'x1' => $plot['left'],
            'y1' => $plot['top'],
            'x2' => $plot['left'],
            'y2' => $plot['bottom'],
        ]);

        foreach (Scales::ticks($yMin, $yMax, $yTicks) as $tick) {
            $y = Scales::linearY($tick, $yMin, $yMax, $plot['top'], $plot['bottom']);
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => $plot['left'] - 8,
                'y' => $y + 3,
                'text-anchor' => 'end',
                'font-size' => 10,
            ], Svg::escape(self::formatTick($tick)));
        }

        $count = count($labels);

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $x = $count === 1
                    ? ($plot['left'] + $plot['width'] / 2)
                    : Scales::linear((float) $i, 0.0, (float) ($count - 1), $plot['left'], $plot['right']);
                $html .= Svg::tag('text', [
                    'class' => $axisClass,
                    'x' => $x,
                    'y' => $plot['bottom'] + 16,
                    'text-anchor' => 'middle',
                    'font-size' => 10,
                ], Svg::escape($labels[$i]));
            }
        }

        if ($xAxis?->labelValue()) {
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => ($plot['left'] + $plot['right']) / 2,
                'y' => $plot['bottom'] + 32,
                'text-anchor' => 'middle',
                'font-size' => 11,
            ], Svg::escape($xAxis->labelValue()));
        }

        if ($yAxis?->labelValue()) {
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => 14,
                'y' => ($plot['top'] + $plot['bottom']) / 2,
                'text-anchor' => 'middle',
                'font-size' => 11,
                'transform' => sprintf('rotate(-90 14 %.2f)', ($plot['top'] + $plot['bottom']) / 2),
            ], Svg::escape($yAxis->labelValue()));
        }

        return $html;
    }

    /**
     * @param array{left: float, top: float, right: float, bottom: float, width: float, height: float} $plot
     * @param list<Marker> $markers
     * @param list<Threshold> $thresholds
     * @param list<Annotation> $annotations
     */
    public static function renderOverlays(
        array $plot,
        float $xMin,
        float $xMax,
        float $yMin,
        float $yMax,
        array $markers,
        array $thresholds,
        array $annotations,
        ChartsTheme $theme,
    ): string {
        $axisClass = implode(' ', $theme->classes('charts.axis'));
        $html = '';

        foreach ($thresholds as $threshold) {
            $y = Scales::linearY($threshold->value(), $yMin, $yMax, $plot['top'], $plot['bottom']);
            $html .= Svg::void('line', [
                'class' => $axisClass,
                'stroke' => '#f59e0b',
                'stroke-dasharray' => '4 3',
                'x1' => $plot['left'],
                'y1' => $y,
                'x2' => $plot['right'],
                'y2' => $y,
            ]);

            if ($threshold->label() !== '') {
                $html .= Svg::tag('text', [
                    'class' => $axisClass,
                    'x' => $plot['right'] - 4,
                    'y' => $y - 4,
                    'text-anchor' => 'end',
                    'font-size' => 10,
                    'fill' => '#f59e0b',
                ], Svg::escape($threshold->label()));
            }
        }

        foreach ($markers as $marker) {
            $x = Scales::linear($marker->value(), $xMin, $xMax, $plot['left'], $plot['right']);
            $html .= Svg::void('line', [
                'class' => $axisClass,
                'stroke' => '#64748b',
                'stroke-dasharray' => '3 3',
                'x1' => $x,
                'y1' => $plot['top'],
                'x2' => $x,
                'y2' => $plot['bottom'],
            ]);

            if ($marker->label() !== '') {
                $html .= Svg::tag('text', [
                    'class' => $axisClass,
                    'x' => $x + 4,
                    'y' => $plot['top'] + 12,
                    'font-size' => 10,
                    'fill' => '#64748b',
                ], Svg::escape($marker->label()));
            }
        }

        foreach ($annotations as $annotation) {
            $y = Scales::linearY($annotation->value(), $yMin, $yMax, $plot['top'], $plot['bottom']);
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => $plot['left'] + 8,
                'y' => $y - 4,
                'font-size' => 10,
                'fill' => '#0d9488',
            ], Svg::escape($annotation->label() !== '' ? $annotation->label() : self::formatTick($annotation->value())));
        }

        return $html;
    }

    /**
     * Map indexed series values to plot coordinates.
     *
     * @return list<array{0: float, 1: float}>
     */
    public static function indexedPoints(Series $series, array $plot, float $yMin, float $yMax): array
    {
        $values = $series->numericValues();
        $count = count($values);
        $points = [];

        for ($i = 0; $i < $count; $i++) {
            $x = $count === 1
                ? ($plot['left'] + $plot['width'] / 2)
                : Scales::linear((float) $i, 0.0, (float) ($count - 1), $plot['left'], $plot['right']);
            $y = Scales::linearY($values[$i], $yMin, $yMax, $plot['top'], $plot['bottom']);
            $points[] = [$x, $y];
        }

        return $points;
    }

    public static function formatTick(float $value): string
    {
        if (abs($value - round($value)) < 0.0001) {
            return (string) (int) round($value);
        }

        return rtrim(rtrim(sprintf('%.2f', $value), '0'), '.');
    }

    /**
     * SVG legend row of colored markers + series names.
     *
     * @param list<Series> $seriesList
     */
    public static function renderSvgLegend(array $seriesList, int $width, ChartsTheme $theme, float $y = 12.0): string
    {
        if ($seriesList === []) {
            return '';
        }

        $class = implode(' ', $theme->classes('charts.legend'));
        $x = 16.0;
        $html = Svg::tag('g', ['class' => $class], '');

        // Rebuild with content
        $inner = '';

        foreach ($seriesList as $i => $series) {
            $color = Scales::colorAt($i, $series->colorValue());
            $inner .= Svg::void('rect', [
                'x' => $x,
                'y' => $y - 6,
                'width' => 10,
                'height' => 10,
                'rx' => 2,
                'fill' => $color,
            ]);
            $inner .= Svg::tag('text', [
                'x' => $x + 14,
                'y' => $y + 2,
                'font-size' => 11,
                'fill' => 'currentColor',
            ], Svg::escape($series->name()));
            $x += 24 + strlen($series->name()) * 6.5;

            if ($x > $width - 40) {
                break;
            }
        }

        return Svg::tag('g', ['class' => $class, 'aria-hidden' => 'true'], $inner);
    }
}
