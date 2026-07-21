<?php

declare(strict_types=1);

namespace Serixa\Admin\Page;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Row of action widgets (typically {@see \Serixa\Component\Button} or
 * {@see \Serixa\Admin\Toolbar\ToolbarAction}), used standalone or via
 * {@see PageHeader::actions()}.
 *
 * @example PageActions::make(Button::make('Export')->secondary(), Button::make('Create')->primary())
 */
final class PageActions extends Component implements AdminWidgetInterface
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
        $actions = new self();
        $actions->setChildren(self::normalizeChildren($children));

        return $actions;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.pageActions'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
