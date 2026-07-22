<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\RadialRenderer;

/**
 * Pie chart (full slices from center).
 */
final class PieChart extends Chart
{
    public static function make(): self
    {
        $chart = new self();
        $chart->showGrid = false;
        $chart->chartHeight = 360;

        return $chart;
    }

    protected function chartType(): string
    {
        return 'pie';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $center = RadialRenderer::center($this->chartWidth, $this->chartHeight);
        $slices = RadialRenderer::slices($this->dataset);

        return RadialRenderer::renderPiePaths(
            $slices,
            $center['cx'],
            $center['cy'],
            $center['radius'],
            0.0,
            $theme,
        );
    }
}
