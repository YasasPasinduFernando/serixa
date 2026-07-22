<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Contracts\CalendarWidgetInterface;
use Serixa\Calendar\Support\DateMath;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\Support\ResolvesCalendarTheme;
use Serixa\Calendar\View\CalendarView;
use Serixa\Component\Component;
use Serixa\Icons\Component\Icon;

/**
 * Prev / today / next + view switcher chrome.
 */
final class CalendarToolbar extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    private string $label = '';

    private string $view = CalendarView::MONTH;

    /** @var list<string> */
    private array $views = CalendarView::ALL;

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param list<string> $views
     */
    public function views(array $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function render(): string
    {
        $this->beforeRender();

        $theme = $this->theme();
        $prev = Icon::make('chevron-left')->decorative()->size('sm')->render();
        $next = Icon::make('chevron-right')->decorative()->size('sm')->render();

        $nav = Html::tag(
            'div',
            ' class="flex items-center gap-2"',
            Html::tag('button', ' type="button" data-serixa-calendar-nav="prev" aria-label="Previous"', $prev)
            . Html::tag('button', ' type="button" data-serixa-calendar-nav="today" aria-label="Today"', 'Today')
            . Html::tag('button', ' type="button" data-serixa-calendar-nav="next" aria-label="Next"', $next),
        );

        $title = Html::tag(
            'div',
            Html::classAttr($theme->classes('calendar.title')) . ' data-serixa-calendar-label="1"',
            Html::escape($this->label !== '' ? $this->label : DateMath::parse('today')->format('F Y')),
        );

        $switcher = '';

        foreach ($this->views as $v) {
            $pressed = $v === $this->view ? 'true' : 'false';
            $switcher .= Html::tag(
                'button',
                ' type="button" data-serixa-calendar-view="' . Html::escape($v) . '" aria-pressed="' . $pressed . '"',
                Html::escape(ucfirst($v)),
            );
        }

        $views = Html::tag('div', ' class="flex gap-1" role="group" aria-label="Calendar views"', $switcher);

        $html = Html::tag(
            'div',
            $this->buildAttributeString($theme->classes('calendar.toolbar'), [
                'data-serixa-calendar-toolbar' => '1',
            ]),
            $nav . $title . $views,
        );

        $this->afterRender();

        return $html;
    }
}
