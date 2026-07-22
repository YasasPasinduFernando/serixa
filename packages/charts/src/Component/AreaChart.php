<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

/**
 * Area chart (filled line) with optional smooth curves.
 */
final class AreaChart extends CartesianChart
{
    private bool $smooth = false;

    public static function make(): self
    {
        return new self();
    }

    public function smooth(bool $smooth = true): self
    {
        $this->smooth = $smooth;

        return $this;
    }

    protected function chartType(): string
    {
        return 'area';
    }

    protected function buildSvg(): string
    {
        return implode('', $this->renderLineSeries($this->smooth, false, true));
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'smooth' => $this->smooth,
        ]);
    }
}
