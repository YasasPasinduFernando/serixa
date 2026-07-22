<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Support\Html;
use Serixa\Calendar\View\CalendarView;

/**
 * Booking/admin scheduler: week or day view plus optional resource strip.
 */
final class Scheduler extends AbstractCalendarWidget
{
    private string $mode = CalendarView::WEEK;

    /** @var list<string|array{id?: string, label?: string}> */
    private array $resources = [];

    public static function make(): self
    {
        return new self();
    }

    public function mode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param list<string|array{id?: string, label?: string}> $resources
     */
    public function resources(array $resources): self
    {
        $this->resources = $resources;

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'scheduler';
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'mode' => $this->mode,
            'resources' => $this->resources,
        ]);
    }

    public function render(): string
    {
        $this->beforeRender();

        $toolbar = CalendarToolbar::make()
            ->label($this->widgetTitle ?? 'Scheduler')
            ->view($this->mode)
            ->views([CalendarView::DAY, CalendarView::WEEK, CalendarView::AGENDA])
            ->render();

        if ($this->mode === CalendarView::DAY) {
            $main = DayView::make();
        } elseif ($this->mode === CalendarView::AGENDA) {
            $main = AgendaView::make();
        } else {
            $main = WeekView::make();
        }

        $main
            ->events($this->events)
            ->focus($this->focus)
            ->today($this->today)
            ->weekStartsOn($this->weekStartsOn);

        $parts = $toolbar . $main->render();

        if ($this->resources !== []) {
            $parts .= ResourceCalendar::make()
                ->resources($this->resources)
                ->events($this->events)
                ->focus($this->focus)
                ->today($this->today)
                ->title('Resources')
                ->render();
        }

        $html = Html::tag('div', $this->rootAttributes(), $parts);

        $this->afterRender();

        return $html;
    }
}
