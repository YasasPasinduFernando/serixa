<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Contracts\CalendarWidgetInterface;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\Support\ResolvesCalendarTheme;
use Serixa\Component\Component;

/**
 * Time-of-day picker (native input + enhancement hook).
 */
final class TimePicker extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    private string $name = 'time';

    private ?string $value = null;

    private ?string $label = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(string $name = 'time'): self
    {
        $picker = new self();
        $picker->name = $name;

        return $picker;
    }

    public function value(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function render(): string
    {
        $this->beforeRender();

        $id = $this->id ?? ('cal-time-' . $this->name);
        $labelHtml = $this->label !== null
            ? Html::tag('label', ' for="' . Html::escape($id) . '"', Html::escape($this->label))
            : '';

        $input = Html::voidTag(
            'input',
            ' type="time" id="' . Html::escape($id) . '" name="' . Html::escape($this->name) . '"'
            . ($this->value !== null ? ' value="' . Html::escape($this->value) . '"' : '')
            . ' data-serixa-calendar-input="time"',
        );

        $html = Html::tag(
            'div',
            $this->buildAttributeString($this->theme()->classes('calendar.picker'), [
                'data-serixa-calendar' => '1',
                'data-serixa-calendar-kind' => 'time-picker',
            ]),
            $labelHtml . $input,
        );

        $this->afterRender();

        return $html;
    }
}
