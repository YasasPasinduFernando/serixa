<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Search + actions row shown above a table.
 *
 * @example TableToolbar::make()->search(SearchField::make())->actions(ExportButton::make())
 */
final class TableToolbar extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

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

        if ($this->search !== null) {
            $nodes[] = $this->search;
        }

        return array_merge($nodes, $this->actionItems);
    }

    public function render(): string
    {
        $searchHtml = $this->search !== null ? $this->renderChild($this->search) : '';

        $actionsHtml = '';

        if ($this->actionItems !== []) {
            $actionsHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.toolbar.group')),
                $this->renderChildren($this->actionItems),
            );
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.tableToolbar'));

        return sprintf('<div%s>%s%s</div>', $attrs, $searchHtml, $actionsHtml);
    }
}
