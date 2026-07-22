<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\RadialRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Gauge with value, min/max, and optional colored zones.
 */
final class Gauge extends Chart
{
    private float $value = 0.0;

    private float $min = 0.0;

    private float $max = 100.0;

    /** @var list<array{from: float, to: float, color: string}> */
    private array $zones = [];

    public static function make(float $value = 0.0): self
    {
        $chart = new self();
        $chart->value = $value;
        $chart->chartWidth = 280;
        $chart->chartHeight = 180;
        $chart->showGrid = false;
        $chart->showLegend = false;
        $chart->tableFallback = false;

        return $chart;
    }

    public function value(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function min(float $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function max(float $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @param list<array{from: float, to: float, color?: string}> $zones
     */
    public function zones(array $zones): self
    {
        $normalized = [];

        foreach ($zones as $i => $zone) {
            $normalized[] = [
                'from' => (float) ($zone['from'] ?? 0),
                'to' => (float) ($zone['to'] ?? 0),
                'color' => (string) ($zone['color'] ?? Scales::colorAt($i)),
            ];
        }

        $this->zones = $normalized;

        return $this;
    }

    protected function chartType(): string
    {
        return 'gauge';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $cx = $this->chartWidth / 2;
        $cy = $this->chartHeight * 0.72;
        $radius = min($this->chartWidth, $this->chartHeight) * 0.42;
        $thickness = 16.0;
        $inner = max(0.0, $radius - $thickness);
        $start = -M_PI * 0.75;
        $end = M_PI * 0.75;
        $html = '';

        $trackClass = implode(' ', $theme->classes('charts.gauge', ['zone' => true]));
        $html .= Svg::void('path', [
            'class' => $trackClass,
            'd' => Svg::arcPath($cx, $cy, $radius, $start, $end, $inner),
            'fill' => '#e2e8f0',
        ]);

        foreach ($this->zones as $i => $zone) {
            $a = RadialRenderer::arcAngles($zone['from'], $this->min, $this->max, $start, $end);
            $b = RadialRenderer::arcAngles($zone['to'], $this->min, $this->max, $start, $end);
            $html .= Svg::void('path', [
                'class' => implode(' ', $theme->classes('charts.gauge', ['zone' => true])),
                'd' => Svg::arcPath($cx, $cy, $radius, $a['end'], $b['end'], $inner),
                'fill' => $zone['color'],
                'data-zone-index' => (string) $i,
            ]);
        }

        $angles = RadialRenderer::arcAngles($this->value, $this->min, $this->max, $start, $end);
        $needleAngle = $angles['end'];
        $nx = $cx + ($radius - $thickness / 2) * cos($needleAngle);
        $ny = $cy + ($radius - $thickness / 2) * sin($needleAngle);
        $needleClass = implode(' ', $theme->classes('charts.gauge', ['zone' => false]));

        $html .= Svg::void('line', [
            'class' => $needleClass . ' charts-point',
            'x1' => $cx,
            'y1' => $cy,
            'x2' => $nx,
            'y2' => $ny,
            'stroke' => Scales::colorAt(0),
            'stroke-width' => 3,
            'stroke-linecap' => 'round',
            'tabindex' => '0',
            'data-value' => (string) $this->value,
            'aria-label' => CartesianRenderer::formatTick($this->value),
        ]);
        $html .= Svg::void('circle', [
            'cx' => $cx,
            'cy' => $cy,
            'r' => 5,
            'fill' => Scales::colorAt(0),
        ]);

        $html .= RadialRenderer::centeredLabel($cx, $cy - 28, CartesianRenderer::formatTick($this->value), $theme, 22);

        return $html;
    }

    /**
     * @return array<string, mixed>
     */
    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'value' => $this->value,
            'min' => $this->min,
            'max' => $this->max,
            'zones' => $this->zones,
        ]);
    }

    protected function buildTable(): string
    {
        return '';
    }
}
