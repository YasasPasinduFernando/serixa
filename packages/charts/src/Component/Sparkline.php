<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Axis\Grid;
use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Compact sparkline — no axes by default, small height.
 */
final class Sparkline extends CartesianChart
{
    private bool $smooth = true;

    public static function make(): self
    {
        $chart = new self();
        $chart->chartWidth = 160;
        $chart->chartHeight = 40;
        $chart->showLegend = false;
        $chart->showGrid = false;
        $chart->gridConfig = Grid::make(false);

        return $chart;
    }

    public function smooth(bool $smooth = true): self
    {
        $this->smooth = $smooth;

        return $this;
    }

    protected function chartType(): string
    {
        return 'sparkline';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $plot = CartesianRenderer::plotBox($this->chartWidth, $this->chartHeight, 2, 2, 2, 2);
        [$yMin, $yMax] = CartesianRenderer::yDomain($this->dataset, $this->yAxisConfig);
        $sparkClass = implode(' ', $theme->classes('charts.sparkline'));
        $html = '';

        foreach ($this->dataset->seriesList() as $i => $series) {
            $points = CartesianRenderer::indexedPoints($series, $plot, $yMin, $yMax);

            if ($points === []) {
                continue;
            }

            $color = Scales::colorAt($i, $series->colorValue());
            $pathD = $this->smooth ? Svg::smoothPath($points) : Svg::linePath($points);
            $html .= Svg::void('path', [
                'class' => $sparkClass,
                'd' => $pathD,
                'fill' => 'none',
                'stroke' => $color,
                'stroke-width' => 1.5,
                'data-series-index' => (string) $i,
            ]);

            $last = $points[count($points) - 1];
            $values = $series->numericValues();
            $html .= Svg::void('circle', [
                'class' => 'charts-point',
                'cx' => $last[0],
                'cy' => $last[1],
                'r' => 2.5,
                'fill' => $color,
                'tabindex' => '0',
                'role' => 'img',
                'data-series-index' => (string) $i,
                'data-value' => (string) ($values[count($values) - 1] ?? 0),
                'aria-label' => $series->name() . ': ' . CartesianRenderer::formatTick((float) ($values[count($values) - 1] ?? 0)),
            ]);
        }

        return $html;
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'smooth' => $this->smooth,
        ]);
    }
}
