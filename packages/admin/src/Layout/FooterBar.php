<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Footer content for the admin shell (copyright, version, links).
 *
 * @example FooterBar::make('© 2026 Acme Inc.')
 */
final class FooterBar extends Component implements AdminWidgetInterface
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
        $footer = new self();
        $footer->setChildren(self::normalizeChildren($children));

        return $footer;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('admin.footer'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
