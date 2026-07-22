<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Horizontal project-style timeline of events.
 */
final class TimelineCalendar extends AbstractCalendarWidget
{
    public static function make(): self
    {
        return new self();
    }

    protected function calendarKind(): string
    {
        return 'timeline';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $from = DateMath::startOfMonth($this->focus);
        $to = DateMath::endOfMonth($this->focus);
        $events = EventNormalizer::inRange($this->events, $from, $to);
        $span = max(1, $to->getTimestamp() - $from->getTimestamp());

        $bars = '';

        foreach ($events as $event) {
            $left = (($event->getStart()->getTimestamp() - $from->getTimestamp()) / $span) * 100;
            $width = max(
                2.0,
                (($event->getEnd()->getTimestamp() - $event->getStart()->getTimestamp()) / $span) * 100,
            );
            $left = max(0.0, min(98.0, $left));
            $width = min(100.0 - $left, $width);
            $color = $event->getColor() ?? '#0284c7';

            $bars .= Html::tag(
                'div',
                ' role="listitem" data-serixa-calendar-event="1" tabindex="0"'
                . ' aria-label="' . Html::escape($event->getTitle()) . '"'
                . ' style="position:absolute;left:' . sprintf('%.2f', $left) . '%;width:'
                . sprintf('%.2f', $width) . '%;top:0.5rem;height:1.75rem;background:'
                . Html::escape($color) . ';color:#fff;font-size:0.75rem;padding:0.25rem 0.5rem;overflow:hidden"',
                Html::escape($event->getTitle()),
            );
        }

        $track = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.timeline'))
            . ' role="list" style="position:relative;height:3rem;border:1px solid #e2e8f0"',
            $bars,
        );

        $axis = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.weekday')),
            Html::escape($from->format('M j') . ' — ' . $to->format('M j, Y')),
        );

        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')),
            Html::escape($this->widgetTitle ?? 'Timeline'),
        );

        $html = Html::tag('div', $this->rootAttributes(), $title . $axis . $track . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
