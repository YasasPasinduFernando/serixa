<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Date picker: native input + mini calendar enhancement target.
 */
final class DatePicker extends AbstractCalendarWidget
{
    private string $name = 'date';

    private ?string $value = null;

    private ?string $label = null;

    public static function make(string $name = 'date'): self
    {
        $picker = new self();
        $picker->name = $name;

        return $picker;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function value(\DateTimeInterface|string|null $value): self
    {
        $this->value = $value === null ? null : DateMath::isoDate(
            is_string($value) ? DateMath::parse($value) : $value,
        );

        if ($this->value !== null) {
            $this->focus = DateMath::parse($this->value);
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
        return 'date-picker';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $id = $this->id ?? ('cal-date-' . $this->name);
        $labelHtml = $this->label !== null
            ? Html::tag('label', ' for="' . Html::escape($id) . '"', Html::escape($this->label))
            : '';

        $input = Html::voidTag(
            'input',
            ' type="date" id="' . Html::escape($id) . '" name="' . Html::escape($this->name) . '"'
            . ($this->value !== null ? ' value="' . Html::escape($this->value) . '"' : '')
            . ' data-serixa-calendar-input="date"',
        );

        $mini = MiniCalendar::make()
            ->focus($this->focus)
            ->today($this->today)
            ->weekStartsOn($this->weekStartsOn)
            ->selected($this->value)
            ->events($this->events)
            ->render();

        $html = Html::tag(
            'div',
            $this->buildAttributeString($theme->classes('calendar.picker'), [
                'data-serixa-calendar' => '1',
                'data-serixa-calendar-kind' => 'date-picker',
                'data-serixa-calendar-config' => Html::jsonAttr($this->configPayload()),
            ]),
            $labelHtml . $input . $mini . $this->liveRegion(),
        );

        $this->afterRender();

        return $html;
    }
}
