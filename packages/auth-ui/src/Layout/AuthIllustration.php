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
 * Visual panel for an illustration or decorative hero in split auth layouts.
 *
 * @example AuthIllustration::make($imageHtml)
 */
final class AuthIllustration extends Component implements AuthWidgetInterface
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
        $illustration = new self();
        $illustration->setChildren(self::normalizeChildren($children));

        return $illustration;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('auth.illustration'));

        return Html::tag('div', $attrs, $this->renderChildren($this->getChildren()));
    }
}
