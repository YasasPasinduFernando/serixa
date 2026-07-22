<?php

declare(strict_types=1);

namespace Serixa\Calendar\Component;

use Serixa\Calendar\Contracts\CalendarWidgetInterface;
use Serixa\Calendar\Event\Event;
use Serixa\Calendar\Support\Html;
use Serixa\Calendar\Support\ResolvesCalendarTheme;
use Serixa\Component\Component;

/**
 * Compact event label / count badge.
 */
final class EventBadge extends Component implements CalendarWidgetInterface
{
    use ResolvesCalendarTheme;

    private function __construct(
        private readonly string $label,
        private readonly ?string $color = null,
    ) {
        parent::__construct();
    }

    public static function make(string|Event $label): self
    {
        if ($label instanceof Event) {
            return new self($label->getTitle(), $label->getColor());
        }

        return new self($label);
    }

    public static function count(int $count): self
    {
        return new self($count === 1 ? '1 event' : $count . ' events');
    }

    public function render(): string
    {
        $this->beforeRender();

        $style = $this->color !== null
            ? ' style="background-color:' . Html::escape($this->color) . ';color:#fff"'
            : '';

        $html = Html::tag(
            'span',
            $this->buildAttributeString($this->theme()->classes('calendar.badge'), []) . $style,
            Html::escape($this->label),
        );

        $this->afterRender();

        return $html;
    }
}
