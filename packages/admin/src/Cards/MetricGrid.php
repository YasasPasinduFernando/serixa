<?php

declare(strict_types=1);

namespace Serixa\Admin\Cards;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Responsive grid of {@see MetricCard} widgets.
 *
 * @example MetricGrid::make(MetricCard::make()->title('Users')->value('1,204'), MetricCard::make()->title('Revenue')->value('$48k'))
 */
final class MetricGrid extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    /** @var list<MetricCard> */
    private array $cards;

    private function __construct(MetricCard ...$cards)
    {
        parent::__construct();
        $this->cards = array_values($cards);
    }

    public static function make(MetricCard ...$cards): self
    {
        return new self(...$cards);
    }

    public function childrenForTree(): array
    {
        return $this->cards;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.metricGrid'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->cards));
    }
}
