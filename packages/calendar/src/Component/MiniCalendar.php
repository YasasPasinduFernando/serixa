<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Compact month grid (no event list — dots / selected day).
 */
final class MiniCalendar extends AbstractCalendarWidget
{
    private ?string $selected = null;

    public static function make(): self
    {
        return new self();
    }

    public function selected(string|\DateTimeInterface|null $selected): self
    {
        $this->selected = $selected === null ? null : DateMath::isoDate(
            is_string($selected) ? DateMath::parse($selected) : $selected,
        );

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'mini';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $grid = DateMath::monthGrid($this->focus, $this->weekStartsOn);
        $labels = DateMath::weekdayLabels($this->weekStartsOn, 'D');
        $focusMonth = (int) $this->focus->format('n');

        $headCells = '';

        foreach ($labels as $label) {
            $headCells .= Html::tag(
                'th',
                ' scope="col"' . Html::classAttr($theme->classes('calendar.weekday')),
                Html::escape(mb_substr($label, 0, 1)),
            );
        }

        $bodyRows = '';

        foreach ($grid as $week) {
            $cells = '';

            foreach ($week as $day) {
                $iso = DateMath::isoDate($day);
                $outside = (int) $day->format('n') !== $focusMonth;
                $isToday = DateMath::sameDay($day, $this->today);
                $isSelected = $this->selected !== null && $this->selected === $iso;
                $hasEvents = EventNormalizer::occurringOn($this->events, $day) !== [];

                $mark = $hasEvents
                    ? Html::tag('span', ' aria-hidden="true" class="block w-1 h-1 mx-auto mt-0.5 rounded-full bg-sky-600"', '')
                    : '';

                $cells .= Html::tag(
                    'td',
                    Html::classAttr($theme->classes('calendar.day', [
                        'outside' => $outside,
                        'today' => $isToday,
                        'selected' => $isSelected,
                    ]))
                    . ' style="height:auto;padding:0.25rem" role="gridcell" tabindex="-1"'
                    . ' data-serixa-calendar-day="' . Html::escape($iso) . '"'
                    . ($isSelected ? ' aria-selected="true"' : ' aria-selected="false"'),
                    Html::tag(
                        'button',
                        ' type="button" data-serixa-calendar-select="' . Html::escape($iso) . '"'
                        . Html::classAttr($theme->classes('calendar.dayNumber', ['today' => $isToday])),
                        Html::escape($day->format('j')),
                    ) . $mark,
                );
            }

            $bodyRows .= Html::tag('tr', ' role="row"', $cells);
        }

        $table = Html::tag(
            'table',
            Html::classAttr($theme->classes('calendar.grid')) . ' role="grid"',
            Html::tag('thead', '', Html::tag('tr', ' role="row"', $headCells))
            . Html::tag('tbody', '', $bodyRows),
        );

        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')),
            Html::escape($this->widgetTitle ?? $this->focus->format('F Y')),
        );

        $html = Html::tag('div', $this->rootAttributes(), $title . $table . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
