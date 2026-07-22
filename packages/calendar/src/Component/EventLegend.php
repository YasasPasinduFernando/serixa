<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Contracts\CalendarWidgetInterface;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\Support\ResolvesCalendarTheme;
use Serixa\Component\Component;

/**
 * Color/label legend for calendar categories.
 */
final class EventLegend extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    /** @var list<array{label: string, color: string}> */
    private array $items = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function item(string $label, string $color): self
    {
        $this->items[] = ['label' => $label, 'color' => $color];

        return $this;
    }

    /**
     * @param list<array{label: string, color: string}> $items
     */
    public function items(array $items): self
    {
        foreach ($items as $item) {
            $this->item((string) $item['label'], (string) $item['color']);
        }

        return $this;
    }

    public function render(): string
    {
        $this->beforeRender();

        $parts = '';

        foreach ($this->items as $item) {
            $swatch = Html::tag(
                'span',
                ' aria-hidden="true" class="inline-block w-3 h-3 rounded-full" style="background-color:'
                . Html::escape($item['color']) . '"',
                '',
            );
            $parts .= Html::tag('span', ' class="inline-flex items-center gap-1"', $swatch . Html::escape($item['label']));
        }

        $html = Html::tag(
            'div',
            $this->buildAttributeString($this->theme()->classes('calendar.legend'), [
                'role' => 'list',
                'aria-label' => 'Event legend',
            ]),
            $parts,
        );

        $this->afterRender();

        return $html;
    }
}
