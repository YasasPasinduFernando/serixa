<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Single-day schedule list.
 */
final class DayView extends AbstractCalendarWidget
{
    public static function make(): self
    {
        return new self();
    }

    protected function calendarKind(): string
    {
        return 'day';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $day = DateMath::startOfDay($this->focus);
        $events = EventNormalizer::occurringOn($this->events, $day);

        usort(
            $events,
            static fn ($a, $b): int => $a->getStart() <=> $b->getStart(),
        );

        $items = '';

        if ($events === []) {
            $items = Html::tag('li', Html::classAttr($theme->classes('calendar.agendaItem')), 'No events');
        } else {
            foreach ($events as $event) {
                $time = $event->isAllDay()
                    ? 'All day'
                    : $event->getStart()->format('H:i') . ' – ' . $event->getEnd()->format('H:i');
                $items .= Html::tag(
                    'li',
                    Html::classAttr($theme->classes('calendar.agendaItem'))
                    . ' data-serixa-calendar-event="1"',
                    Html::tag('time', ' datetime="' . Html::escape(DateMath::isoDateTime($event->getStart())) . '"', Html::escape($time))
                    . $this->renderEventChip($event),
                );
            }
        }

        $list = Html::tag(
            'ol',
            Html::classAttr($theme->classes('calendar.agenda'))
            . ' data-serixa-calendar-day="' . Html::escape(DateMath::isoDate($day)) . '"',
            $items,
        );

        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')),
            Html::escape($this->widgetTitle ?? $day->format('l, F j, Y')),
        );

        $html = Html::tag('div', $this->rootAttributes(), $title . $list . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
