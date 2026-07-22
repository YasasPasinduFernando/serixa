<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\RadialRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Radar chart — categories from labels, one polygon per series.
 */
final class RadarChart extends Chart
{
    public static function make(): self
    {
        $chart = new self();
        $chart->showGrid = true;

        return $chart;
    }

    protected function chartType(): string
    {
        return 'radar';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $center = RadialRenderer::center($this->chartWidth, $this->chartHeight, 40.0);
        $labels = $this->dataset->labelList();
        $n = max(3, count($labels));

        if (count($labels) < $n) {
            for ($i = count($labels); $i < $n; $i++) {
                $labels[] = (string) ($i + 1);
            }
        }

        $max = 0.0;

        foreach ($this->dataset->seriesList() as $series) {
            foreach ($series->numericValues() as $value) {
                $max = max($max, abs($value));
            }
        }

        if ($max <= 0.0) {
            $max = 1.0;
        }

        $html = '';
        $axisClass = implode(' ', $theme->classes('charts.axis'));
        $gridClass = implode(' ', $theme->classes('charts.grid'));

        // Concentric polygons
        foreach ([0.25, 0.5, 0.75, 1.0] as $ring) {
            $ringPoints = [];

            for ($i = 0; $i < $n; $i++) {
                $angle = -M_PI_2 + ($i / $n) * 2 * M_PI;
                $r = $center['radius'] * $ring;
                $ringPoints[] = [
                    $center['cx'] + $r * cos($angle),
                    $center['cy'] + $r * sin($angle),
                ];
            }

            $html .= Svg::void('path', [
                'class' => $gridClass,
                'd' => Svg::linePath($ringPoints, true),
                'fill' => 'none',
                'stroke' => 'currentColor',
                'stroke-opacity' => '0.25',
            ]);
        }

        // Spokes + labels
        for ($i = 0; $i < $n; $i++) {
            $angle = -M_PI_2 + ($i / $n) * 2 * M_PI;
            $x2 = $center['cx'] + $center['radius'] * cos($angle);
            $y2 = $center['cy'] + $center['radius'] * sin($angle);
            $html .= Svg::void('line', [
                'class' => $axisClass,
                'x1' => $center['cx'],
                'y1' => $center['cy'],
                'x2' => $x2,
                'y2' => $y2,
                'stroke' => 'currentColor',
                'stroke-opacity' => '0.35',
            ]);
            $lx = $center['cx'] + ($center['radius'] + 14) * cos($angle);
            $ly = $center['cy'] + ($center['radius'] + 14) * sin($angle);
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => $lx,
                'y' => $ly,
                'text-anchor' => 'middle',
                'dominant-baseline' => 'middle',
                'font-size' => 10,
            ], Svg::escape($labels[$i]));
        }

        foreach ($this->dataset->seriesList() as $si => $series) {
            $color = Scales::colorAt($si, $series->colorValue());
            $class = implode(' ', $theme->classes('charts.series', ['index' => $si]));
            $values = $series->numericValues();
            $points = [];

            for ($i = 0; $i < $n; $i++) {
                $value = abs($values[$i] ?? 0.0);
                $t = $value / $max;
                $angle = -M_PI_2 + ($i / $n) * 2 * M_PI;
                $r = $center['radius'] * $t;
                $points[] = [
                    $center['cx'] + $r * cos($angle),
                    $center['cy'] + $r * sin($angle),
                ];
            }

            $html .= Svg::void('path', [
                'class' => $class,
                'd' => Svg::linePath($points, true),
                'fill' => $color,
                'fill-opacity' => '0.2',
                'stroke' => $color,
                'stroke-width' => 2,
                'data-series-index' => (string) $si,
            ]);

            foreach ($points as $pi => $point) {
                $html .= Svg::void('circle', [
                    'class' => 'charts-point',
                    'cx' => $point[0],
                    'cy' => $point[1],
                    'r' => 3,
                    'fill' => $color,
                    'tabindex' => '0',
                    'role' => 'img',
                    'data-series-index' => (string) $si,
                    'data-point-index' => (string) $pi,
                    'data-label' => $labels[$pi],
                    'data-value' => (string) ($values[$pi] ?? 0),
                    'aria-label' => sprintf('%s, %s: %s', $series->name(), $labels[$pi], CartesianRenderer::formatTick((float) ($values[$pi] ?? 0))),
                ]);
            }
        }

        return $html;
    }
}
