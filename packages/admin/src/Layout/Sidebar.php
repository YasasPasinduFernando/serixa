<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Primary navigation landmark (`<nav>`), composed of {@see SidebarGroup} and/or
 * {@see SidebarItem} children.
 *
 * @example Sidebar::make()->children(SidebarItem::make('Dashboard')->icon('home')->href('/admin'))
 */
final class Sidebar extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $ariaLabel = 'Primary';

    /** @var list<WidgetInterface|string> */
    private array $items = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function ariaLabel(string $label): self
    {
        $this->ariaLabel = $label;

        return $this;
    }

    public function child(WidgetInterface|string $child): self
    {
        $this->items[] = $child;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public function children(WidgetInterface|string|array ...$children): self
    {
        if (count($children) === 1 && is_array($children[0])) {
            /** @var list<WidgetInterface|string> $list */
            $list = array_values($children[0]);

            foreach ($list as $child) {
                $this->child($child);
            }

            return $this;
        }

        foreach ($children as $child) {
            if (is_array($child)) {
                continue;
            }

            $this->child($child);
        }

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->items;
    }

    public function render(): string
    {
        $listHtml = '';

        foreach ($this->items as $item) {
            $listHtml .= $item instanceof SidebarItem
                ? sprintf('<li>%s</li>', $item->renderAsChild(false))
                : sprintf('<li>%s</li>', $this->renderChild($item));
        }

        $navAttrs = $this->buildAttributeString(
            $this->theme()->classes('admin.sidebar'),
            ['aria-label' => $this->ariaLabel],
        );

        $listAttrs = $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.list'));

        return sprintf('<nav%s><ul%s>%s</ul></nav>', $navAttrs, $listAttrs, $listHtml);
    }
}
