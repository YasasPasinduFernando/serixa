<?php

declare(strict_types=1);

namespace Serixa\Admin\Toolbar;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Groups related {@see ToolbarAction} widgets inside a {@see Toolbar}.
 *
 * @example ToolbarGroup::make(ToolbarAction::make('Export')->icon('cloud-arrow-down'))
 */
final class ToolbarGroup extends Component implements AdminWidgetInterface
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
        $group = new self();
        $group->setChildren(self::normalizeChildren($children));

        return $group;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.toolbar.group'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
