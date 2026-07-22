<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Layout;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Themed wrapper for auth page footer content (links, legal).
 *
 * @example AuthFooter::make(Link::make('Sign up')->href('/register'))
 */
final class AuthFooter extends Component implements AuthWidgetInterface
{
    use HasChildren;
    use ResolvesAuthTheme;

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
        $attrs = $this->buildAttributeString($this->theme()->classes('auth.footer'));

        return Html::tag('footer', $attrs, $this->renderChildren($this->getChildren()));
    }
}
