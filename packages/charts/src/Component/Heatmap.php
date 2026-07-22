<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\Svg;

/**
 * Heatmap — rows from series names, columns from labels, cell values from series.
 */
final class Heatmap extends Chart
{
    public static function make(): self
    {
        $chart = new self();
        $chart->showGrid = false;

        return $chart;
    }

    protected function chartType(): string
    {
        return 'heatmap';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $labels = $this->dataset->labelList();
        $seriesList = $this->dataset->seriesList();
        $cols = max(1, count($labels));
        $rows = max(1, count($seriesList));

        $padLeft = 72.0;
        $padTop = 24.0;
        $padRight = 16.0;
        $padBottom = 40.0;
        $plotW = max(1.0, $this->chartWidth - $padLeft - $padRight);
        $plotH = max(1.0, $this->chartHeight - $padTop - $padBottom);
        $cellW = $plotW / $cols;
        $cellH = $plotH / $rows;

        $all = [];

        foreach ($seriesList as $series) {
            foreach ($series->numericValues() as $value) {
                $all[] = $value;
            }
        }

        $min = $all === [] ? 0.0 : min($all);
        $max = $all === [] ? 1.0 : max($all);

        if ($min === $max) {
            $max = $min + 1;
        }

        $html = '';
        $axisClass = implode(' ', $theme->classes('charts.axis'));

        foreach ($seriesList as $ri => $series) {
            $values = $series->numericValues();
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => $padLeft - 8,
                'y' => $padTop + $ri * $cellH + $cellH / 2 + 3,
                'text-anchor' => 'end',
                'font-size' => 10,
            ], Svg::escape($series->name()));

            for ($ci = 0; $ci < $cols; $ci++) {
                $value = $values[$ci] ?? 0.0;
                $t = ($value - $min) / ($max - $min);
                $intensity = (int) round($t * 9);
                $class = implode(' ', $theme->classes('charts.heatmap', ['intensity' => $intensity]));
                $color = self::heatColor($t);
                $label = $labels[$ci] ?? (string) $ci;

                $html .= Svg::void('rect', [
                    'class' => $class . ' charts-point',
                    'x' => $padLeft + $ci * $cellW,
                    'y' => $padTop + $ri * $cellH,
                    'width' => max(1.0, $cellW - 1),
                    'height' => max(1.0, $cellH - 1),
                    'fill' => $color,
                    'tabindex' => '0',
                    'role' => 'img',
                    'data-series-index' => (string) $ri,
                    'data-point-index' => (string) $ci,
                    'data-label' => $label,
                    'data-value' => (string) $value,
                    'aria-label' => sprintf('%s, %s: %s', $series->name(), $label, CartesianRenderer::formatTick($value)),
                ]);
            }
        }

        for ($ci = 0; $ci < $cols; $ci++) {
            $html .= Svg::tag('text', [
                'class' => $axisClass,
                'x' => $padLeft + $ci * $cellW + $cellW / 2,
                'y' => $padTop + $plotH + 16,
                'text-anchor' => 'middle',
                'font-size' => 10,
            ], Svg::escape($labels[$ci] ?? (string) $ci));
        }

        return $html;
    }

    private static function heatColor(float $t): string
    {
        $t = max(0.0, min(1.0, $t));
        $r = (int) round(226 + (13 - 226) * $t);
        $g = (int) round(232 + (148 - 232) * $t);
        $b = (int) round(240 + (136 - 240) * $t);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
