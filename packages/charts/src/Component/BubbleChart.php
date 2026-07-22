<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Dataset\Point;
use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Bubble chart — scatter with optional Point radius.
 */
final class BubbleChart extends CartesianChart
{
    public static function make(): self
    {
        return new self();
    }

    protected function chartType(): string
    {
        return 'bubble';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $plot = CartesianRenderer::plotBox($this->chartWidth, $this->chartHeight);
        [$xMin, $xMax] = CartesianRenderer::xDomainFromPoints($this->dataset, $this->xAxisConfig);
        [$yMin, $yMax] = CartesianRenderer::yDomain($this->dataset, $this->yAxisConfig);

        $html = $this->renderChrome($plot, $yMin, $yMax, $xMin, $xMax);

        foreach ($this->dataset->seriesList() as $si => $series) {
            $color = Scales::colorAt($si, $series->colorValue());
            $class = implode(' ', $theme->classes('charts.series', ['index' => $si]));

            foreach ($series->rawValues() as $pi => $value) {
                if (! $value instanceof Point) {
                    continue;
                }

                $x = Scales::linear($value->x(), $xMin, $xMax, $plot['left'], $plot['right']);
                $y = Scales::linearY($value->y(), $yMin, $yMax, $plot['top'], $plot['bottom']);
                $r = max(3.0, $value->r() ?? 8.0);

                $html .= Svg::void('circle', [
                    'class' => $class . ' charts-point',
                    'cx' => $x,
                    'cy' => $y,
                    'r' => $r,
                    'fill' => $color,
                    'fill-opacity' => '0.65',
                    'stroke' => $color,
                    'tabindex' => '0',
                    'role' => 'img',
                    'data-series-index' => (string) $si,
                    'data-point-index' => (string) $pi,
                    'data-label' => $series->name(),
                    'data-value' => (string) $value->y(),
                    'aria-label' => sprintf(
                        '%s: (%s, %s, r=%s)',
                        $series->name(),
                        CartesianRenderer::formatTick($value->x()),
                        CartesianRenderer::formatTick($value->y()),
                        CartesianRenderer::formatTick($r),
                    ),
                ]);
            }
        }

        return $html;
    }
}
