<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use DateTimeImmutable;
use DateTimeInterface;
use Serixa\Calendar\Contracts\CalendarWidgetInterface;
use Serixa\Calendar\Event\Event;
use Serixa\Calendar\Event\EventCollection;
use Serixa\Calendar\Event\EventNormalizer;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\Support\ResolvesCalendarTheme;
use Serixa\Component\Component;

/**
 * Shared SSR calendar widget: events, focus date, PE attrs.
 */
abstract class AbstractCalendarWidget extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    /** @var list<Event> */
    protected array $events = [];

    protected DateTimeImmutable $focus;

    protected DateTimeImmutable $today;

    protected int $weekStartsOn = 1;

    protected ?string $ariaSummary = null;

    protected ?string $widgetTitle = null;

    protected function __construct()
    {
        parent::__construct();
        $this->focus = new DateTimeImmutable('today');
        $this->today = new DateTimeImmutable('today');
    }

    public function events(mixed $events): static
    {
        $this->events = EventNormalizer::normalize($events);

        return $this;
    }

    public function addEvent(Event ...$events): static
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function focus(DateTimeInterface|string $focus): static
    {
        $this->focus = DateMath::parse($focus);

        return $this;
    }

    public function today(DateTimeInterface|string $today): static
    {
        $this->today = DateMath::parse($today);

        return $this;
    }

    public function weekStartsOn(int $day): static
    {
        $this->weekStartsOn = max(1, min(7, $day));

        return $this;
    }

    public function summary(string $summary): static
    {
        $this->ariaSummary = $summary;

        return $this;
    }

    public function title(string $title): static
    {
        $this->widgetTitle = $title;

        return $this;
    }

    /**
     * @return list<Event>
     */
    protected function eventList(): array
    {
        return $this->events;
    }

    protected function collection(): EventCollection
    {
        return EventCollection::make($this->events);
    }

    abstract protected function calendarKind(): string;

    /**
     * @return array<string, mixed>
     */
    protected function configPayload(): array
    {
        return [
            'kind' => $this->calendarKind(),
            'focus' => DateMath::isoDate($this->focus),
            'today' => DateMath::isoDate($this->today),
            'weekStartsOn' => $this->weekStartsOn,
            'events' => $this->collection()->toArray(),
            'title' => $this->widgetTitle,
        ];
    }

    protected function renderEventChip(Event $event, bool $compact = false): string
    {
        $theme = $this->theme();
        $class = implode(' ', $theme->classes('calendar.event', ['compact' => $compact]));
        $style = $event->getColor() !== null ? ' style="background-color:' . Html::escape($event->getColor()) . ';color:#fff"' : '';
        $label = Html::escape($event->getTitle());
        $inner = $label;

        if ($event->getUrl() !== null && $event->getUrl() !== '') {
            $inner = Html::tag('a', ' href="' . Html::escape($event->getUrl()) . '"', $label);
        }

        return Html::tag(
            'div',
            ' class="' . Html::escape($class) . '" data-serixa-calendar-event="1" data-event-id="'
            . Html::escape((string) ($event->getId() ?? '')) . '"' . $style
            . ' role="listitem" tabindex="0" aria-label="' . Html::escape($event->getTitle()) . '"',
            $inner,
        );
    }

    protected function liveRegion(): string
    {
        $class = implode(' ', $this->theme()->classes('calendar.live'));

        return Html::tag(
            'div',
            ' data-serixa-calendar-live="1" aria-live="polite" class="' . Html::escape($class) . '"',
            '',
        );
    }

    /**
     * @param array<string, string|bool|null> $extra
     */
    protected function rootAttributes(array $extra = []): string
    {
        $theme = $this->theme();
        $aria = $this->ariaSummary
            ?? ($this->widgetTitle !== null && $this->widgetTitle !== ''
                ? $this->widgetTitle
                : 'Calendar');

        return $this->buildAttributeString(
            $theme->classes('calendar.root'),
            array_merge([
                'data-serixa-calendar' => '1',
                'data-serixa-calendar-kind' => $this->calendarKind(),
                'data-serixa-calendar-config' => Html::jsonAttr($this->configPayload()),
                'role' => 'region',
                'aria-label' => $aria,
            ], $extra),
        );
    }
}
