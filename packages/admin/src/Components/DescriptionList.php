<?php

declare(strict_types=1);

namespace Serixa\Admin\Components;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Key/value definition list (`<dl>`) of {@see DescriptionItem} entries.
 *
 * @example DescriptionList::make(DescriptionItem::make('Plan', 'Pro'), DescriptionItem::make('Seats', '25'))
 */
final class DescriptionList extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    /** @var list<DescriptionItem> */
    private array $items;

    private function __construct(DescriptionItem ...$items)
    {
        parent::__construct();
        $this->items = array_values($items);
    }

    public static function make(DescriptionItem ...$items): self
    {
        return new self(...$items);
    }

    public function childrenForTree(): array
    {
        return $this->items;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.descriptionList'));

        return sprintf('<dl%s>%s</dl>', $attrs, $this->renderChildren($this->items));
    }
}
