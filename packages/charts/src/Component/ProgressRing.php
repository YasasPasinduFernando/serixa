<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\RadialRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Single-value progress ring (0–100 by default).
 */
final class ProgressRing extends Chart
{
    private float $value = 0.0;

    private float $min = 0.0;

    private float $max = 100.0;

    private float $thickness = 12.0;

    public static function make(float $value = 0.0): self
    {
        $chart = new self();
        $chart->value = $value;
        $chart->chartWidth = 160;
        $chart->chartHeight = 160;
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

    public function thickness(float $thickness): self
    {
        $this->thickness = max(1.0, $thickness);

        return $this;
    }

    protected function chartType(): string
    {
        return 'progress-ring';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $center = RadialRenderer::center($this->chartWidth, $this->chartHeight, 16.0);
        $angles = RadialRenderer::arcAngles($this->value, $this->min, $this->max, -M_PI_2, -M_PI_2 + 2 * M_PI);
        $inner = max(0.0, $center['radius'] - $this->thickness);
        $trackClass = implode(' ', $theme->classes('charts.ring', ['track' => true]));
        $valueClass = implode(' ', $theme->classes('charts.ring', ['track' => false]));

        $html = Svg::void('circle', [
            'class' => $trackClass,
            'cx' => $center['cx'],
            'cy' => $center['cy'],
            'r' => ($center['radius'] + $inner) / 2,
            'fill' => 'none',
            'stroke' => '#e2e8f0',
            'stroke-width' => $this->thickness,
        ]);

        if ($angles['ratio'] > 0.0001) {
            if ($angles['ratio'] >= 0.9999) {
                $html .= Svg::void('circle', [
                    'class' => $valueClass . ' charts-point',
                    'cx' => $center['cx'],
                    'cy' => $center['cy'],
                    'r' => ($center['radius'] + $inner) / 2,
                    'fill' => 'none',
                    'stroke' => Scales::colorAt(0),
                    'stroke-width' => $this->thickness,
                    'tabindex' => '0',
                    'data-value' => (string) $this->value,
                    'aria-label' => CartesianRenderer::formatTick($this->value),
                ]);
            } else {
                $html .= Svg::void('path', [
                    'class' => $valueClass . ' charts-point',
                    'd' => Svg::arcPath(
                        $center['cx'],
                        $center['cy'],
                        $center['radius'],
                        $angles['start'],
                        $angles['end'],
                        $inner,
                    ),
                    'fill' => Scales::colorAt(0),
                    'tabindex' => '0',
                    'data-value' => (string) $this->value,
                    'aria-label' => CartesianRenderer::formatTick($this->value),
                ]);
            }
        }

        $label = CartesianRenderer::formatTick($this->value);

        if ($this->max == 100.0 && $this->min == 0.0) {
            $label .= '%';
        }

        $html .= RadialRenderer::centeredLabel($center['cx'], $center['cy'], $label, $theme, 20);

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
            'thickness' => $this->thickness,
        ]);
    }

    protected function buildTable(): string
    {
        return '';
    }
}
