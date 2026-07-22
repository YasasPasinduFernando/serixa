<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Shared path/bar scaffolding for cartesian charts.
 */
abstract class CartesianChart extends Chart
{
    /**
     * Render shared plot chrome (grid, axes, overlays, optional legend).
     *
     * @param array{left: float, top: float, right: float, bottom: float, width: float, height: float} $plot
     */
    protected function renderChrome(array $plot, float $yMin, float $yMax, float $xMin = 0.0, ?float $xMax = null): string
    {
        $theme = $this->theme();
        $labels = $this->dataset->labelList();
        $xMax ??= (float) max(1, count($labels) - 1);

        $html = '';

        if ($this->showLegend && count($this->dataset->seriesList()) > 1) {
            $html .= CartesianRenderer::renderSvgLegend(
                $this->dataset->seriesList(),
                $this->chartWidth,
                $theme,
            );
        }

        $html .= CartesianRenderer::renderGrid($plot, $yMin, $yMax, $theme, $this->resolvedGrid());
        $html .= CartesianRenderer::renderAxes(
            $plot,
            $labels,
            $yMin,
            $yMax,
            $theme,
            $this->xAxisConfig,
            $this->yAxisConfig,
        );
        $html .= CartesianRenderer::renderOverlays(
            $plot,
            $xMin,
            $xMax,
            $yMin,
            $yMax,
            $this->markers,
            $this->thresholds,
            $this->annotations,
            $theme,
        );

        return $html;
    }

    /**
     * @return list<string>
     */
    protected function renderLineSeries(bool $smooth, bool $markers, bool $area = false): array
    {
        $theme = $this->theme();
        $plot = CartesianRenderer::plotBox($this->chartWidth, $this->chartHeight);
        [$yMin, $yMax] = CartesianRenderer::yDomain($this->dataset, $this->yAxisConfig);
        $fragments = [];
        $fragments[] = $this->renderChrome($plot, $yMin, $yMax);

        foreach ($this->dataset->seriesList() as $i => $series) {
            $points = CartesianRenderer::indexedPoints($series, $plot, $yMin, $yMax);

            if ($points === []) {
                continue;
            }

            $color = Scales::colorAt($i, $series->colorValue());
            $class = implode(' ', $theme->classes('charts.series', ['index' => $i]));
            $pathD = $smooth ? Svg::smoothPath($points) : Svg::linePath($points);

            if ($area) {
                $last = $points[count($points) - 1];
                $first = $points[0];
                $areaD = ($smooth ? Svg::smoothPath($points) : Svg::linePath($points))
                    . sprintf(' L%.2f %.2f L%.2f %.2f Z', $last[0], $plot['bottom'], $first[0], $plot['bottom']);
                $fragments[] = Svg::void('path', [
                    'class' => $class,
                    'd' => $areaD,
                    'fill' => $color,
                    'fill-opacity' => '0.25',
                    'stroke' => 'none',
                    'data-series-index' => (string) $i,
                ]);
            }

            $fragments[] = Svg::void('path', [
                'class' => $class,
                'd' => $pathD,
                'fill' => 'none',
                'stroke' => $color,
                'stroke-width' => 2,
                'data-series-index' => (string) $i,
            ]);

            // Always emit focusable points for progressive enhancement; markers control visibility.
            $r = $markers ? 3.5 : 2.5;
            $opacity = $markers ? '1' : '0';

            foreach ($points as $pi => $point) {
                $values = $series->numericValues();
                $label = $this->dataset->labelList()[$pi] ?? (string) $pi;
                $fragments[] = Svg::void('circle', [
                    'class' => 'charts-point',
                    'cx' => $point[0],
                    'cy' => $point[1],
                    'r' => $r,
                    'fill' => $color,
                    'opacity' => $opacity,
                    'tabindex' => '0',
                    'role' => 'img',
                    'data-series-index' => (string) $i,
                    'data-point-index' => (string) $pi,
                    'data-label' => $label,
                    'data-value' => (string) ($values[$pi] ?? 0),
                    'aria-label' => sprintf('%s, %s: %s', $series->name(), $label, CartesianRenderer::formatTick((float) ($values[$pi] ?? 0))),
                ]);
            }
        }

        return $fragments;
    }

    /**
     * @return list<string>
     */
    protected function renderBarSeries(bool $horizontal, bool $stacked): array
    {
        $theme = $this->theme();
        $plot = CartesianRenderer::plotBox($this->chartWidth, $this->chartHeight);
        $labels = $this->dataset->labelList();
        $seriesList = $this->dataset->seriesList();
        $categoryCount = max(1, count($labels), ...array_map(
            static fn (Series $s): int => count($s->numericValues()),
            $seriesList,
        ));

        if ($stacked) {
            $totals = array_fill(0, $categoryCount, 0.0);

            foreach ($seriesList as $series) {
                foreach ($series->numericValues() as $i => $value) {
                    $totals[$i] = ($totals[$i] ?? 0.0) + $value;
                }
            }

            $yMin = min(0.0, ...$totals);
            $yMax = max(0.0, ...$totals);
            [$yMin, $yMax] = Scales::niceDomain($yMin === $yMax ? 0.0 : $yMin, $yMax === 0.0 && $yMin === 0.0 ? 1.0 : $yMax);
        } else {
            [$yMin, $yMax] = CartesianRenderer::yDomain($this->dataset, $this->yAxisConfig);
        }

        $fragments = [];
        $fragments[] = $this->renderChrome($plot, $yMin, $yMax);

        $seriesCount = max(1, count($seriesList));
        $band = $plot['width'] / $categoryCount;
        $groupPad = $band * 0.15;
        $inner = $band - 2 * $groupPad;
        $barW = $stacked ? $inner : $inner / $seriesCount;
        $stacks = array_fill(0, $categoryCount, 0.0);

        foreach ($seriesList as $si => $series) {
            $color = Scales::colorAt($si, $series->colorValue());
            $class = implode(' ', $theme->classes('charts.series', ['index' => $si]));
            $values = $series->numericValues();

            for ($i = 0; $i < $categoryCount; $i++) {
                $value = $values[$i] ?? 0.0;
                $label = $labels[$i] ?? (string) $i;

                if ($horizontal) {
                    $y = $plot['top'] + $i * ($plot['height'] / $categoryCount) + $groupPad;
                    $h = ($plot['height'] / $categoryCount) - 2 * $groupPad;
                    $h = $stacked ? $h : $h / $seriesCount;
                    $yOffset = $stacked ? 0.0 : $si * $h;
                    $x0 = Scales::linear(0.0, $yMin, $yMax, $plot['left'], $plot['right']);
                    $x1 = Scales::linear($value, $yMin, $yMax, $plot['left'], $plot['right']);

                    if ($stacked) {
                        $base = $stacks[$i];
                        $x0 = Scales::linear($base, $yMin, $yMax, $plot['left'], $plot['right']);
                        $x1 = Scales::linear($base + $value, $yMin, $yMax, $plot['left'], $plot['right']);
                        $stacks[$i] += $value;
                    }

                    $fragments[] = Svg::void('rect', [
                        'class' => $class . ' charts-point',
                        'x' => min($x0, $x1),
                        'y' => $y + $yOffset,
                        'width' => abs($x1 - $x0),
                        'height' => max(1.0, $h * ($stacked ? 1 : 1)),
                        'fill' => $color,
                        'tabindex' => '0',
                        'role' => 'img',
                        'data-series-index' => (string) $si,
                        'data-point-index' => (string) $i,
                        'data-label' => $label,
                        'data-value' => (string) $value,
                        'aria-label' => sprintf('%s, %s: %s', $series->name(), $label, CartesianRenderer::formatTick($value)),
                    ]);
                } else {
                    $x = $plot['left'] + $i * $band + $groupPad + ($stacked ? 0.0 : $si * $barW);
                    $zeroY = Scales::linearY(0.0, $yMin, $yMax, $plot['top'], $plot['bottom']);

                    if ($stacked) {
                        $base = $stacks[$i];
                        $y0 = Scales::linearY($base, $yMin, $yMax, $plot['top'], $plot['bottom']);
                        $y1 = Scales::linearY($base + $value, $yMin, $yMax, $plot['top'], $plot['bottom']);
                        $stacks[$i] += $value;
                    } else {
                        $y0 = $zeroY;
                        $y1 = Scales::linearY($value, $yMin, $yMax, $plot['top'], $plot['bottom']);
                    }

                    $fragments[] = Svg::void('rect', [
                        'class' => $class . ' charts-point',
                        'x' => $x,
                        'y' => min($y0, $y1),
                        'width' => max(1.0, $barW),
                        'height' => max(1.0, abs($y1 - $y0)),
                        'fill' => $color,
                        'tabindex' => '0',
                        'role' => 'img',
                        'data-series-index' => (string) $si,
                        'data-point-index' => (string) $i,
                        'data-label' => $label,
                        'data-value' => (string) $value,
                        'aria-label' => sprintf('%s, %s: %s', $series->name(), $label, CartesianRenderer::formatTick($value)),
                    ]);
                }
            }
        }

        return $fragments;
    }
}
