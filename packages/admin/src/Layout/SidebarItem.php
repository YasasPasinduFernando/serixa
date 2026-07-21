<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Icons\Component\Icon;

/**
 * A single sidebar navigation entry, optionally with an icon, badge and nested children.
 *
 * @example SidebarItem::make('Dashboard')->icon('home')->href('/admin')->active()
 */
final class SidebarItem extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private string $label;

    private ?string $icon = null;

    private string $href = '#';

    private bool $active = false;

    private ?string $badge = null;

    /** @var list<SidebarItem> */
    private array $items = [];

    private function __construct(string $label)
    {
        parent::__construct();
        $this->label = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function href(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function active(bool $active = true): self
    {
        $this->active = $active;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function badge(?string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function children(SidebarItem ...$items): self
    {
        $this->items = array_values($items);

        return $this;
    }

    public function childrenForTree(): array
    {
        return $this->items;
    }

    public function render(): string
    {
        return $this->renderAsChild(false);
    }

    /**
     * @internal Called by {@see Sidebar} / {@see SidebarGroup} so nesting depth is known.
     */
    public function renderAsChild(bool $nested): string
    {
        $iconHtml = '';

        if ($this->icon !== null) {
            $iconHtml = sprintf(
                '<span%s aria-hidden="true">%s</span>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.item.icon')),
                $this->renderChild(Icon::make($this->icon)->size('sm')),
            );
        }

        $labelHtml = sprintf(
            '<span%s>%s</span>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.item.label')),
            $this->escape($this->label),
        );

        $badgeHtml = '';

        if ($this->badge !== null) {
            $badgeHtml = sprintf(
                '<span%s>%s</span>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.item.badge')),
                $this->escape($this->badge),
            );
        }

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('admin.sidebar.item', ['active' => $this->active, 'nested' => $nested]),
            [
                'href' => $this->href,
                'aria-current' => $this->active ? 'page' : null,
            ],
        );

        $html = sprintf('<a%s>%s%s%s</a>', $attrs, $iconHtml, $labelHtml, $badgeHtml);

        if ($this->items !== []) {
            $childItems = '';

            foreach ($this->items as $item) {
                $childItems .= sprintf('<li>%s</li>', $item->renderAsChild(true));
            }

            $html .= sprintf(
                '<ul%s>%s</ul>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.item.children')),
                $childItems,
            );
        }

        return $html;
    }
}
