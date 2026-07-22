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
 * Split-layout aside panel for marketing or brand messaging.
 *
 * @example AuthAside::make('Secure access for your team')
 */
final class AuthAside extends Component implements AuthWidgetInterface
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
        $aside = new self();
        $aside->setChildren(self::normalizeChildren($children));

        return $aside;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('auth.aside'));

        return Html::tag('aside', $attrs, $this->renderChildren($this->getChildren()));
    }
}
