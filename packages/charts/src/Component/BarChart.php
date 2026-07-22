<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

/**
 * Vertical or horizontal bar chart, optionally stacked.
 */
final class BarChart extends CartesianChart
{
    private bool $horizontal = false;

    private bool $stacked = false;

    public static function make(): self
    {
        return new self();
    }

    public function horizontal(bool $horizontal = true): self
    {
        $this->horizontal = $horizontal;

        return $this;
    }

    public function stacked(bool $stacked = true): self
    {
        $this->stacked = $stacked;

        return $this;
    }

    protected function chartType(): string
    {
        return 'bar';
    }

    protected function buildSvg(): string
    {
        return implode('', $this->renderBarSeries($this->horizontal, $this->stacked));
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'horizontal' => $this->horizontal,
            'stacked' => $this->stacked,
        ]);
    }
}
