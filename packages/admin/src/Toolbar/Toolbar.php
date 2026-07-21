<?php

declare(strict_types=1);

namespace Serixa\Admin\Toolbar;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * `role="toolbar"` container for grouped page actions.
 *
 * @example Toolbar::make(ToolbarGroup::make(...), ToolbarGroup::make(...))
 */
final class Toolbar extends Component implements AdminWidgetInterface
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
        $toolbar = new self();
        $toolbar->setChildren(self::normalizeChildren($children));

        return $toolbar;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('admin.toolbar'),
            ['role' => 'toolbar'],
        );

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
