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
 * Centered auth card shell for form content.
 *
 * @example AuthCard::make($form)->child(AuthFooter::make(...))
 */
final class AuthCard extends Component implements AuthWidgetInterface
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
        $card = new self();
        $card->setChildren(self::normalizeChildren($children));

        return $card;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('auth.card'));

        return Html::tag('div', $attrs, $this->renderChildren($this->getChildren()));
    }
}
