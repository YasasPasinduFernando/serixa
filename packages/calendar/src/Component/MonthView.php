<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Month grid with weekday headers and event chips.
 */
final class MonthView extends AbstractCalendarWidget
{
    private int $maxEventsPerDay = 3;

    public static function make(): self
    {
        return new self();
    }

    public function maxEventsPerDay(int $max): self
    {
        $this->maxEventsPerDay = max(0, $max);

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'month';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $grid = DateMath::monthGrid($this->focus, $this->weekStartsOn);
        $labels = DateMath::weekdayLabels($this->weekStartsOn);
        $focusMonth = (int) $this->focus->format('n');

        $headCells = '';

        foreach ($labels as $label) {
            $headCells .= Html::tag(
                'th',
                ' scope="col"' . Html::classAttr($theme->classes('calendar.weekday')) . ' role="columnheader"',
                Html::escape($label),
            );
        }

        $thead = Html::tag('thead', '', Html::tag('tr', ' role="row"', $headCells));
        $bodyRows = '';

        foreach ($grid as $week) {
            $cells = '';

            foreach ($week as $day) {
                $iso = DateMath::isoDate($day);
                $outside = (int) $day->format('n') !== $focusMonth;
                $isToday = DateMath::sameDay($day, $this->today);
                $dayEvents = EventNormalizer::occurringOn($this->events, $day);
                $shown = array_slice($dayEvents, 0, $this->maxEventsPerDay);
                $more = count($dayEvents) - count($shown);

                $chips = '';

                foreach ($shown as $event) {
                    $chips .= $this->renderEventChip($event, true);
                }

                if ($more > 0) {
                    $chips .= Html::tag(
                        'div',
                        Html::classAttr($theme->classes('calendar.badge')),
                        Html::escape('+' . $more . ' more'),
                    );
                }

                $number = Html::tag(
                    'span',
                    Html::classAttr($theme->classes('calendar.dayNumber', ['today' => $isToday])),
                    Html::escape($day->format('j')),
                );

                $cells .= Html::tag(
                    'td',
                    Html::classAttr($theme->classes('calendar.day', [
                        'outside' => $outside,
                        'today' => $isToday,
                    ]))
                    . ' role="gridcell" tabindex="-1" data-serixa-calendar-day="' . Html::escape($iso) . '"'
                    . ' aria-label="' . Html::escape($day->format('l, F j, Y')) . '"'
                    . ($isToday ? ' aria-current="date"' : ''),
                    $number . Html::tag('div', ' role="list"', $chips),
                );
            }

            $bodyRows .= Html::tag('tr', ' role="row"', $cells);
        }

        $table = Html::tag(
            'table',
            Html::classAttr($theme->classes('calendar.grid'))
            . ' role="grid" data-serixa-calendar-grid="month" aria-labelledby=""',
            $thead . Html::tag('tbody', '', $bodyRows),
        );

        $title = '';

        if ($this->widgetTitle !== null && $this->widgetTitle !== '') {
            $title = Html::tag(
                'div',
                Html::classAttr($theme->classes('calendar.title')),
                Html::escape($this->widgetTitle),
            );
        } else {
            $title = Html::tag(
                'div',
                Html::classAttr($theme->classes('calendar.title')),
                Html::escape($this->focus->format('F Y')),
            );
        }

        $html = Html::tag('div', $this->rootAttributes(), $title . $table . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
