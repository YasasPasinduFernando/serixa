<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Contracts\CalendarWidgetInterface;
use Serixa\Calendar\Event\Event;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\Support\ResolvesCalendarTheme;
use Serixa\Component\Component;

/**
 * SSR popover panel for an event (shown/hidden via PE).
 */
final class EventPopover extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    private bool $open = false;

    private function __construct(private readonly Event $event)
    {
        parent::__construct();
    }

    public static function make(Event $event): self
    {
        return new self($event);
    }

    public function open(bool $open = true): self
    {
        $this->open = $open;

        return $this;
    }

    public function render(): string
    {
        $this->beforeRender();

        $e = $this->event;
        $body = EventCard::make($e)->render();
        $hidden = $this->open ? '' : ' hidden';

        $html = Html::tag(
            'div',
            $this->buildAttributeString($this->theme()->classes('calendar.popover'), [
                'data-serixa-calendar-popover' => '1',
                'data-event-id' => $e->getId(),
                'role' => 'dialog',
                'aria-label' => $e->getTitle(),
            ]) . $hidden
            . ' data-start="' . Html::escape(DateMath::isoDateTime($e->getStart())) . '"',
            $body,
        );

        $this->afterRender();

        return $html;
    }
}
