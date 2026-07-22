<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Chronological agenda across a date range (default: focus month).
 */
final class AgendaView extends AbstractCalendarWidget
{
    private ?\DateTimeImmutable $rangeEnd = null;

    public static function make(): self
    {
        return new self();
    }

    public function until(\DateTimeInterface|string $end): self
    {
        $this->rangeEnd = DateMath::parse($end);

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'agenda';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $from = DateMath::startOfMonth($this->focus);
        $to = $this->rangeEnd ?? DateMath::endOfMonth($this->focus);
        $events = EventNormalizer::inRange($this->events, $from, $to);

        usort(
            $events,
            static fn ($a, $b): int => $a->getStart() <=> $b->getStart(),
        );

        $items = '';

        if ($events === []) {
            $items = Html::tag('li', Html::classAttr($theme->classes('calendar.agendaItem')), 'No upcoming events');
        } else {
            foreach ($events as $event) {
                $when = $event->getStart()->format('D, M j')
                    . ($event->isAllDay() ? ' · All day' : ' · ' . $event->getStart()->format('H:i'));
                $items .= Html::tag(
                    'li',
                    Html::classAttr($theme->classes('calendar.agendaItem')),
                    Html::tag('div', '', Html::escape($when)) . EventCard::make($event)->render(),
                );
            }
        }

        $list = Html::tag('ol', Html::classAttr($theme->classes('calendar.agenda')), $items);
        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')),
            Html::escape($this->widgetTitle ?? 'Agenda'),
        );

        $html = Html::tag('div', $this->rootAttributes(), $title . $list . $this->liveRegion());

        $this->afterRender();

        return $html;
    }
}
