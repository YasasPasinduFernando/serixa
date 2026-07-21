<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;

/**
 * Labeled group of {@see SidebarItem} entries, optionally collapsible via a
 * native `<details>`/`<summary>` disclosure — no JavaScript required.
 *
 * @example SidebarGroup::make()->label('Content')->items(SidebarItem::make('Posts')->href('/posts'))
 */
final class SidebarGroup extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private ?string $label = null;

    /** @var list<SidebarItem> */
    private array $items = [];

    private bool $collapsible = false;

    private bool $open = true;

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

    public function items(SidebarItem ...$items): self
    {
        $this->items = array_values($items);

        return $this;
    }

    public function collapsible(bool $collapsible = true): self
    {
        $this->collapsible = $collapsible;

        return $this;
    }

    public function open(bool $open = true): self
    {
        $this->open = $open;

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
            $listHtml .= sprintf('<li>%s</li>', $item->renderAsChild(false));
        }

        $list = sprintf(
            '<ul%s>%s</ul>',
            $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.group.list')),
            $listHtml,
        );

        if ($this->collapsible) {
            $summary = $this->label !== null
                ? sprintf(
                    '<summary%s>%s</summary>',
                    $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.group.summary')),
                    $this->escape($this->label),
                )
                : '';

            $attrs = $this->buildAttributeString(
                $this->theme()->classes('admin.sidebar.group'),
                ['open' => $this->open ? true : null],
            );

            return sprintf('<details%s>%s%s</details>', $attrs, $summary, $list);
        }

        $labelHtml = $this->label !== null
            ? sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.sidebar.group.label')),
                $this->escape($this->label),
            )
            : '';

        return sprintf(
            '<div%s>%s%s</div>',
            $this->buildAttributeString($this->theme()->classes('admin.sidebar.group')),
            $labelHtml,
            $list,
        );
    }
}
