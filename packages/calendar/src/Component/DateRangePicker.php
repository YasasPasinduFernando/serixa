<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Start/end date range picker.
 */
final class DateRangePicker extends AbstractCalendarWidget
{
    private string $startName = 'start';

    private string $endName = 'end';

    private ?string $startValue = null;

    private ?string $endValue = null;

    private ?string $label = null;

    public static function make(string $startName = 'start', string $endName = 'end'): self
    {
        $picker = new self();
        $picker->startName = $startName;
        $picker->endName = $endName;

        return $picker;
    }

    public function values(\DateTimeInterface|string|null $start, \DateTimeInterface|string|null $end): self
    {
        $this->startValue = $start === null ? null : DateMath::isoDate(
            is_string($start) ? DateMath::parse($start) : $start,
        );
        $this->endValue = $end === null ? null : DateMath::isoDate(
            is_string($end) ? DateMath::parse($end) : $end,
        );

        if ($this->startValue !== null) {
            $this->focus = DateMath::parse($this->startValue);
        }

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'date-range-picker';
    }

    protected function configPayload(): array
    {
        return array_merge(parent::configPayload(), [
            'start' => $this->startValue,
            'end' => $this->endValue,
        ]);
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $heading = $this->label !== null
            ? Html::tag('div', Html::classAttr($theme->classes('calendar.title')), Html::escape($this->label))
            : '';

        $inputs = Html::voidTag(
            'input',
            ' type="date" name="' . Html::escape($this->startName) . '"'
            . ($this->startValue !== null ? ' value="' . Html::escape($this->startValue) . '"' : '')
            . ' data-serixa-calendar-input="start" aria-label="Start date"',
        ) . Html::voidTag(
            'input',
            ' type="date" name="' . Html::escape($this->endName) . '"'
            . ($this->endValue !== null ? ' value="' . Html::escape($this->endValue) . '"' : '')
            . ' data-serixa-calendar-input="end" aria-label="End date"',
        );

        $mini = MiniCalendar::make()
            ->focus($this->focus)
            ->today($this->today)
            ->weekStartsOn($this->weekStartsOn)
            ->selected($this->startValue)
            ->render();

        $html = Html::tag(
            'div',
            $this->buildAttributeString($theme->classes('calendar.picker'), [
                'data-serixa-calendar' => '1',
                'data-serixa-calendar-kind' => 'date-range-picker',
                'data-serixa-calendar-config' => Html::jsonAttr($this->configPayload()),
            ]),
            $heading . $inputs . $mini . $this->liveRegion(),
        );

        $this->afterRender();

        return $html;
    }
}
