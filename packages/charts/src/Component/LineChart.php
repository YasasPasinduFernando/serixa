<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

/**
 * Line chart with optional smooth curves and visible markers.
 */
final class LineChart extends CartesianChart
{
    private bool $smooth = false;

    private bool $showMarkers = true;

    public static function make(): self
    {
        return new self();
    }

    public function smooth(bool $smooth = true): self
    {
        $this->smooth = $smooth;

        return $this;
    }

    public function markers(bool $markers = true): self
    {
        $this->showMarkers = $markers;

        return $this;
    }

    protected function chartType(): string
    {
        return 'line';
    }

    protected function buildSvg(): string
    {
        return implode('', $this->renderLineSeries($this->smooth, $this->showMarkers, false));
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'smooth' => $this->smooth,
            'markers' => $this->showMarkers,
        ]);
    }
}
