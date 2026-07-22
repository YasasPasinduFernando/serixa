<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;

/**
 * Combined date + time picker.
 */
final class DateTimePicker extends AbstractCalendarWidget
{
    private string $name = 'datetime';

    private ?string $value = null;

    private ?string $label = null;

    public static function make(string $name = 'datetime'): self
    {
        $picker = new self();
        $picker->name = $name;

        return $picker;
    }

    public function value(\DateTimeInterface|string|null $value): self
    {
        if ($value === null) {
            $this->value = null;

            return $this;
        }

        $dt = is_string($value) ? DateMath::parse($value) : DateMath::parse($value);
        $this->value = $dt->format('Y-m-d\TH:i');
        $this->focus = $dt;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    protected function calendarKind(): string
    {
        return 'datetime-picker';
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $id = $this->id ?? ('cal-dt-' . $this->name);
        $labelHtml = $this->label !== null
            ? Html::tag('label', ' for="' . Html::escape($id) . '"', Html::escape($this->label))
            : '';

        $input = Html::voidTag(
            'input',
            ' type="datetime-local" id="' . Html::escape($id) . '" name="' . Html::escape($this->name) . '"'
            . ($this->value !== null ? ' value="' . Html::escape($this->value) . '"' : '')
            . ' data-serixa-calendar-input="datetime"',
        );

        $mini = MiniCalendar::make()
            ->focus($this->focus)
            ->today($this->today)
            ->weekStartsOn($this->weekStartsOn)
            ->selected($this->value !== null ? substr($this->value, 0, 10) : null)
            ->render();

        $html = Html::tag(
            'div',
            $this->buildAttributeString($theme->classes('calendar.picker'), [
                'data-serixa-calendar' => '1',
                'data-serixa-calendar-kind' => 'datetime-picker',
                'data-serixa-calendar-config' => Html::jsonAttr($this->configPayload()),
            ]),
            $labelHtml . $input . $mini . $this->liveRegion(),
        );

        $this->afterRender();

        return $html;
    }
}
