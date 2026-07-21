<?php

declare(strict_types=1);

namespace Serixa\Admin\Tables;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Wrapping row for filter fields (selects, inputs) placed above a table.
 *
 * @example TableFilters::make(Select::make()->name('status'), Select::make()->name('role'))
 */
final class TableFilters extends Component implements AdminWidgetInterface
{
    use HasChildren;
    use ResolvesAdminTheme;

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $filters = new self();
        $filters->setChildren(self::normalizeChildren($children));

        return $filters;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.tableFilters'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
