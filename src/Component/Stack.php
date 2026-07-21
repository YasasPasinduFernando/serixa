<?php

declare(strict_types=1);

namespace NovaPHP\Component;

use NovaPHP\Component\Concerns\HasChildren;
use NovaPHP\Component\Concerns\ResolvesTheme;
use NovaPHP\Contracts\WidgetInterface;

/**
 * Layered layout context (Flutter Stack).
 *
 * v1 paints children in DOM order inside a relative container.
 * Future: absolute Positioned children without changing this public API.
 */
final class Stack extends Component
{
    use HasChildren;
    use ResolvesTheme;

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $stack = new self();
        $stack->setChildren(self::normalizeChildren($children));

        return $stack;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('stack'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
