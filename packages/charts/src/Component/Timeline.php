<?php

declare(strict_types=1);

namespace Serixa\Charts\Component;

use Serixa\Charts\Dataset\Normalizer;
use Serixa\Charts\Support\CartesianRenderer;
use Serixa\Charts\Support\Scales;
use Serixa\Charts\Support\Svg;

/**
 * Timeline of events — [{label, at}] or labels+values as positions.
 */
final class Timeline extends Chart
{
    /** @var list<array{label: string, at: float}> */
    private array $events = [];

    public static function make(): self
    {
        $chart = new self();
        $chart->chartHeight = 120;
        $chart->showGrid = false;
        $chart->showLegend = false;

        return $chart;
    }

    /**
     * @param list<array{label?: string, at?: float|int|string}>|iterable<mixed> $events
     */
    public function events(iterable $events): self
    {
        $list = [];

        foreach (Normalizer::toList($events) as $event) {
            if (is_array($event)) {
                $list[] = [
                    'label' => (string) ($event['label'] ?? ''),
                    'at' => (float) ($event['at'] ?? 0),
                ];

                continue;
            }

            if (is_object($event) && isset($event->label, $event->at)) {
                $list[] = [
                    'label' => (string) $event->label,
                    'at' => (float) $event->at,
                ];
            }
        }

        $this->events = $list;

        return $this;
    }

    protected function chartType(): string
    {
        return 'timeline';
    }

    protected function buildSvg(): string
    {
        $theme = $this->theme();
        $events = $this->resolveEvents();
        $class = implode(' ', $theme->classes('charts.timeline'));
        $pad = 40.0;
        $y = $this->chartHeight / 2;
        $left = $pad;
        $right = $this->chartWidth - $pad;

        $ats = array_column($events, 'at');
        $min = $ats === [] ? 0.0 : (float) min($ats);
        $max = $ats === [] ? 1.0 : (float) max($ats);

        if ($min === $max) {
            $max = $min + 1;
        }

        $html = Svg::void('line', [
            'class' => $class,
            'x1' => $left,
            'y1' => $y,
            'x2' => $right,
            'y2' => $y,
            'stroke' => 'currentColor',
            'stroke-width' => 2,
        ]);

        foreach ($events as $i => $event) {
            $x = Scales::linear($event['at'], $min, $max, $left, $right);
            $color = Scales::colorAt($i);
            $html .= Svg::void('circle', [
                'class' => 'charts-point',
                'cx' => $x,
                'cy' => $y,
                'r' => 6,
                'fill' => $color,
                'tabindex' => '0',
                'role' => 'img',
                'data-point-index' => (string) $i,
                'data-label' => $event['label'],
                'data-value' => (string) $event['at'],
                'aria-label' => sprintf('%s at %s', $event['label'], CartesianRenderer::formatTick($event['at'])),
            ]);
            $html .= Svg::tag('text', [
                'class' => implode(' ', $theme->classes('charts.axis')),
                'x' => $x,
                'y' => $y - 14,
                'text-anchor' => 'middle',
                'font-size' => 11,
            ], Svg::escape($event['label']));
        }

        return $html;
    }

    /**
     * @return list<array{label: string, at: float}>
     */
    private function resolveEvents(): array
    {
        if ($this->events !== []) {
            return $this->events;
        }

        $labels = $this->dataset->labelList();
        $seriesList = $this->dataset->seriesList();
        $values = $seriesList !== [] ? $seriesList[0]->numericValues() : [];
        $events = [];

        foreach ($labels as $i => $label) {
            $events[] = [
                'label' => $label,
                'at' => (float) ($values[$i] ?? $i),
            ];
        }

        if ($events === [] && $values !== []) {
            foreach ($values as $i => $value) {
                $events[] = [
                    'label' => (string) ($i + 1),
                    'at' => $value,
                ];
            }
        }

        return $events;
    }

    /**
     * @return array<string, mixed>
     */
    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'events' => $this->resolveEvents(),
        ]);
    }
}
