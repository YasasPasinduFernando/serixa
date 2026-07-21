<?php

declare(strict_types=1);

namespace Serixa\Admin\Layout;

use Serixa\Admin\Contracts\AdminWidgetInterface;
use Serixa\Admin\Support\AssertsAdminOption;
use Serixa\Admin\Support\ResolvesAdminTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Centered, max-width-constrained container for page content.
 *
 * @example PageContainer::make(Page::make(...))->maxWidth('lg')
 */
final class PageContainer extends Component implements AdminWidgetInterface
{
    use AssertsAdminOption;
    use HasChildren;
    use ResolvesAdminTheme;

    private string $maxWidth = 'xl';

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $container = new self();
        $container->setChildren(self::normalizeChildren($children));

        return $container;
    }

    public function maxWidth(string $maxWidth): self
    {
        $this->assertOption($maxWidth, ['sm', 'md', 'lg', 'xl', '2xl', 'full'], 'maxWidth');
        $this->maxWidth = $maxWidth;

        return $this;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('admin.pageContainer', ['maxWidth' => $this->maxWidth]),
        );

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
