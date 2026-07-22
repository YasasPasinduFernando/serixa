<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Resource × day grid (rooms, staff, etc.). Apps supply resource ids on events.
 */
final class ResourceCalendar extends AbstractCalendarWidget
{
    /** @var list<array{id: string, label: string}> */
    private array $resources = [];

    public static function make(): self
    {
        return new self();
    }

    /**
     * @param list<string|array{id?: string, label?: string}> $resources
     */
    public function resources(array $resources): self
    {
        $normalized = [];

        foreach ($resources as $resource) {
            if (is_string($resource)) {
                $normalized[] = ['id' => $resource, 'label' => $resource];

                continue;
            }

            $id = (string) ($resource['id'] ?? $resource['label'] ?? '');
            $label = (string) ($resource['label'] ?? $id);
            $normalized[] = ['id' => $id, 'label' => $label];
        }

        $this->resources = $normalized;

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'resource';
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'resources' => $this->resources,
        ]);
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $day = DateMath::startOfDay($this->focus);
        $dayEvents = EventNormalizer::occurringOn($this->events, $day);

        $head = Html::tag('th', ' scope="col"' . Html::classAttr($theme->classes('calendar.weekday')), 'Resource');
        $head .= Html::tag(
            'th',
            ' scope="col"' . Html::classAttr($theme->classes('calendar.weekday')),
            Html::escape($day->format('D, M j')),
        );

        $rows = '';

        foreach ($this->resources as $resource) {
            $matched = array_values(array_filter(
                $dayEvents,
                static fn ($e): bool => $e->getResource() === $resource['id'],
            ));
            $chips = '';

            foreach ($matched as $event) {
                $chips .= $this->renderEventChip($event);
            }

            $rows .= Html::tag(
                'tr',
                ' role="row"',
                Html::tag('th', ' scope="row"' . Html::classAttr($theme->classes('calendar.weekday')), Html::escape($resource['label']))
                . Html::tag(
                    'td',
                    Html::classAttr($theme->classes('calendar.day'))
                    . ' role="gridcell" data-resource="' . Html::escape($resource['id']) . '"',
                    $chips === '' ? Html::tag('span', ' class="sr-only"', 'Available') : $chips,
                ),
            );
        }

        if ($this->resources === []) {
            $rows = Html::tag('tr', '', Html::tag('td', ' colspan="2"', 'No resources configured'));
        }

        $table = Html::tag(
            'table',
            Html::classAttr($theme->classes('calendar.resource')) . ' role="grid"',
            Html::tag('thead', '', Html::tag('tr', ' role="row"', $head))
            . Html::tag('tbody', '', $rows),
        );

        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')),
            Html::escape($this->widgetTitle ?? 'Resources'),
        );

        $html = Html::tag('div', $this->rootAttributes(), $title . $table . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
