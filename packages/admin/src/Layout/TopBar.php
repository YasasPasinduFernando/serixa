<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Top application bar: brand/title, optional search slot, trailing actions.
 *
 * @example TopBar::make()->brand('Acme Admin')->search(SearchField::make())->actions(UserMenu::make(...))
 */
final class TopBar extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private WidgetInterface|string|null $brand = null;

    private WidgetInterface|string|null $search = null;

    /** @var list<WidgetInterface|string> */
    private array $actionItems = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function brand(WidgetInterface|string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Convenience alias for a plain-text {@see brand()}.
     */
    public function title(string $title): self
    {
        return $this->brand($title);
    }

    public function search(WidgetInterface|string $search): self
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$actions
     */
    public function actions(WidgetInterface|string|array ...$actions): self
    {
        if (count($actions) === 1 && is_array($actions[0])) {
            /** @var list<WidgetInterface|string> $list */
            $list = array_values($actions[0]);
            $this->actionItems = $list;

            return $this;
        }

        /** @var list<WidgetInterface|string> $list */
        $list = [];

        foreach ($actions as $action) {
            if (is_array($action)) {
                continue;
            }

            $list[] = $action;
        }

        $this->actionItems = $list;

        return $this;
    }

    public function childrenForTree(): array
    {
        $nodes = [];

        foreach ([$this->brand, $this->search] as $slot) {
            if ($slot !== null) {
                $nodes[] = $slot;
            }
        }

        return array_merge($nodes, $this->actionItems);
    }

    public function render(): string
    {
        $brandHtml = '';

        if ($this->brand !== null) {
            $brandHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.topBar.brand')),
                $this->renderChild($this->brand),
            );
        }

        $searchHtml = '';

        if ($this->search !== null) {
            $searchHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.topBar.search')),
                $this->renderChild($this->search),
            );
        }

        $actionsHtml = '';

        if ($this->actionItems !== []) {
            $actionsHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.topBar.actions')),
                $this->renderChildren($this->actionItems),
            );
        }

        return sprintf(
            '<div%s>%s%s%s</div>',
            $this->buildAttributeString($this->theme()->classes('admin.topBar')),
            $brandHtml,
            $searchHtml,
            $actionsHtml,
        );
    }
}
