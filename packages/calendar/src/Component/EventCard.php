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
 * Accessible event summary card.
 */
final class EventCard extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    private function __construct(private readonly Event $event)
    {
        parent::__construct();
    }

    public static function make(Event $event): self
    {
        return new self($event);
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $e = $this->event;
        $time = $e->isAllDay()
            ? 'All day'
            : $e->getStart()->format('M j, Y H:i') . ' – ' . $e->getEnd()->format('H:i');

        $parts = Html::tag('div', ' class="font-medium"', Html::escape($e->getTitle()));
        $parts .= Html::tag('time', ' datetime="' . Html::escape(DateMath::isoDateTime($e->getStart())) . '"', Html::escape($time));

        if ($e->getDescription() !== null && $e->getDescription() !== '') {
            $parts .= Html::tag('p', ' class="text-sm opacity-80"', Html::escape($e->getDescription()));
        }

        if ($e->getUrl() !== null && $e->getUrl() !== '') {
            $parts .= Html::tag('a', ' href="' . Html::escape($e->getUrl()) . '"', 'Details');
        }

        $style = $e->getColor() !== null
            ? ' style="border-left:4px solid ' . Html::escape($e->getColor()) . '"'
            : '';

        $html = Html::tag(
            'article',
            $this->buildAttributeString($theme->classes('calendar.card'), [
                'data-serixa-calendar-event' => '1',
                'data-event-id' => $e->getId(),
            ]) . $style,
            $parts,
        );

        $this->afterRender();

        return $html;
    }
}
