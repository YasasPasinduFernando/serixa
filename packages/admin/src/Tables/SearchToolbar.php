<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\SearchField;
use Serixa\Contracts\WidgetInterface;

/**
 * Search input row, defaulting to the framework {@see SearchField}.
 *
 * @example SearchToolbar::make()->placeholder('Search users…')->actions(ExportButton::make())
 */
final class SearchToolbar extends Component implements AdminWidgetInterface
{
    use ResolvesAdminTheme;

    private WidgetInterface $search;

    /** @var list<WidgetInterface|string> */
    private array $actionItems = [];

    private function __construct()
    {
        parent::__construct();
        $this->search = SearchField::make();
    }

    public static function make(): self
    {
        return new self();
    }

    public function search(WidgetInterface $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        if ($this->search instanceof SearchField) {
            $this->search->placeholder($placeholder);
        }

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
        return array_merge([$this->search], $this->actionItems);
    }

    public function render(): string
    {
        $actionsHtml = '';

        if ($this->actionItems !== []) {
            $actionsHtml = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('admin.toolbar.group')),
                $this->renderChildren($this->actionItems),
            );
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('admin.searchToolbar'));

        return sprintf('<div%s>%s%s</div>', $attrs, $this->renderChild($this->search), $actionsHtml);
    }
}
