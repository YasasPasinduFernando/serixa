<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Seven-day week columns with timed events.
 */
final class WeekView extends AbstractCalendarWidget
{
    public static function make(): self
    {
        return new self();
    }

    protected function calendarKind(): string
    {
        return 'week';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $days = DateMath::weekDays($this->focus, $this->weekStartsOn);
        $from = $days[0];
        $to = $days[6];
        $rangeEvents = EventNormalizer::inRange($this->events, DateMath::startOfDay($from), DateMath::endOfDay($to));

        $head = '';
        $body = '';

        foreach ($days as $day) {
            $isToday = DateMath::sameDay($day, $this->today);
            $head .= Html::tag(
                'th',
                ' scope="col"' . Html::classAttr($theme->classes('calendar.weekday'))
                . ($isToday ? ' aria-current="date"' : ''),
                Html::escape($day->format('D j')),
            );

            $dayEvents = EventNormalizer::occurringOn($rangeEvents, $day);
            $chips = '';

            foreach ($dayEvents as $event) {
                $time = $event->isAllDay()
                    ? 'All day'
                    : $event->getStart()->format('H:i');
                $chips .= $this->renderEventChip($event);
                $chips .= Html::tag('div', Html::classAttr($theme->classes('calendar.badge')), Html::escape($time));
            }

            $body .= Html::tag(
                'td',
                Html::classAttr($theme->classes('calendar.day', ['today' => $isToday]))
                . ' role="gridcell" data-serixa-calendar-day="' . Html::escape(DateMath::isoDate($day)) . '"',
                $chips === '' ? Html::tag('span', ' class="sr-only"', 'No events') : $chips,
            );
        }

        $table = Html::tag(
            'table',
            Html::classAttr($theme->classes('calendar.timeGrid')) . ' role="grid"',
            Html::tag('thead', '', Html::tag('tr', ' role="row"', $head))
            . Html::tag('tbody', '', Html::tag('tr', ' role="row"', $body)),
        );

        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')),
            Html::escape($this->widgetTitle ?? ($from->format('M j') . ' – ' . $to->format('M j, Y'))),
        );

        $html = Html::tag('div', $this->rootAttributes(), $title . $table . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
