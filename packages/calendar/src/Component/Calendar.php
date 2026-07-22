<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Exceptions\CalendarException;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\View\CalendarView;

/**
 * Primary calendar shell: toolbar + selected view.
 */
final class Calendar extends AbstractCalendarWidget
{
    private string $view = CalendarView::MONTH;

    private bool $showToolbar = true;

    private bool $showLegend = false;

    /** @var list<array{label: string, color: string}> */
    private array $legendItems = [];

    public static function make(): self
    {
        return new self();
    }

    public function view(string $view): self
    {
        if (! CalendarView::isValid($view)) {
            throw new CalendarException(sprintf('Unknown calendar view "%s".', $view));
        }

        $this->view = $view;

        return $this;
    }

    public function toolbar(bool $show = true): self
    {
        $this->showToolbar = $show;

        return $this;
    }

    /**
     * @param list<array{label: string, color: string}> $items
     */
    public function legend(array $items = []): self
    {
        $this->showLegend = true;
        $this->legendItems = $items;

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'calendar';
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'view' => $this->view,
        ]);
    }

    public function render(): string
    {
        $this->beforeRender();

        $parts = '';

        if ($this->showToolbar) {
            $label = match ($this->view) {
                CalendarView::WEEK => DateMath::startOfWeek($this->focus, $this->weekStartsOn)->format('M j')
                    . ' – ' . DateMath::endOfWeek($this->focus, $this->weekStartsOn)->format('M j, Y'),
                CalendarView::DAY => $this->focus->format('l, F j, Y'),
                CalendarView::AGENDA => $this->widgetTitle ?? 'Agenda',
                default => $this->focus->format('F Y'),
            };

            $parts .= CalendarToolbar::make()
                ->label($label)
                ->view($this->view)
                ->render();
        }

        $viewWidget = match ($this->view) {
            CalendarView::WEEK => WeekView::make(),
            CalendarView::DAY => DayView::make(),
            CalendarView::AGENDA => AgendaView::make(),
            default => MonthView::make(),
        };

        $viewWidget
            ->events($this->events)
            ->focus($this->focus)
            ->today($this->today)
            ->weekStartsOn($this->weekStartsOn);

        if ($this->widgetTitle !== null) {
            $viewWidget->title($this->widgetTitle);
        }

        if ($this->ariaSummary !== null) {
            $viewWidget->summary($this->ariaSummary);
        }

        $parts .= $viewWidget->render();

        if ($this->showLegend) {
            $legend = EventLegend::make();

            if ($this->legendItems !== []) {
                $legend->items($this->legendItems);
            }

            $parts .= $legend->render();
        }

        $html = Html::tag(
            'div',
            $this->rootAttributes(['data-serixa-calendar-shell' => '1']),
            $parts,
        );

        $this->afterRender();

        return $html;
    }
}
