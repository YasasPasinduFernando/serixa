<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\RadialRenderer;
use Serixa\Charts\Support\Svg;

/**
 * Simple polar area chart — wedge radius proportional to value.
 */
final class PolarChart extends Chart
{
    public static function make(): self
    {
        $chart = new self();
        $chart->showGrid = false;

        return $chart;
    }

    protected function chartType(): string
    {
        return 'polar';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $center = RadialRenderer::center($this->chartWidth, $this->chartHeight);
        $slices = RadialRenderer::slices($this->dataset);
        $max = 0.0;

        foreach ($slices as $slice) {
            $max = max($max, $slice['value']);
        }

        if ($max <= 0.0) {
            $max = 1.0;
        }

        $n = max(1, count($slices));
        $sweep = 2 * M_PI / $n;
        $angle = -M_PI_2;
        $html = '';

        foreach ($slices as $slice) {
            $r = $center['radius'] * ($slice['value'] / $max);
            $end = $angle + $sweep;
            $class = implode(' ', $theme->classes('charts.slice', ['index' => $slice['index']]));

            $html .= Svg::void('path', [
                'class' => $class,
                'd' => Svg::arcPath($center['cx'], $center['cy'], $r, $angle, $end, 0.0),
                'fill' => $slice['color'],
                'fill-opacity' => '0.85',
                'stroke' => '#fff',
                'stroke-width' => 1,
                'tabindex' => '0',
                'role' => 'img',
                'data-series-index' => (string) $slice['index'],
                'data-label' => $slice['label'],
                'data-value' => (string) $slice['value'],
                'aria-label' => $slice['label'] . ': ' . CartesianRenderer::formatTick($slice['value']),
            ]);

            $angle = $end;
        }

        return $html;
    }
}
