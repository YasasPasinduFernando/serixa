<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\RadialRenderer;

/**
 * Donut chart with configurable ring thickness (outer − inner radius).
 */
final class DonutChart extends Chart
{
    private float $thickness = 28.0;

    public static function make(): self
    {
        $chart = new self();
        $chart->showGrid = false;

        return $chart;
    }

    public function thickness(float $thickness): self
    {
        $this->thickness = max(1.0, $thickness);

        return $this;
    }

    protected function chartType(): string
    {
        return 'donut';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $center = RadialRenderer::center($this->chartWidth, $this->chartHeight);
        $inner = max(0.0, $center['radius'] - $this->thickness);
        $slices = RadialRenderer::slices($this->dataset);

        return RadialRenderer::renderPiePaths(
            $slices,
            $center['cx'],
            $center['cy'],
            $center['radius'],
            $inner,
            $theme,
        );
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'thickness' => $this->thickness,
        ]);
    }
}
