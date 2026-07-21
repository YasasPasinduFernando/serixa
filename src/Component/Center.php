<?php

declare(strict_types=1);

namespace NovaPHP\Component;

use NovaPHP\Component\Concerns\HasChildren;
use NovaPHP\Component\Concerns\ResolvesTheme;
use NovaPHP\Contracts\WidgetInterface;

/**
 * Centers children on both axes via flex.
 */
final class Center extends Component
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
        $center = new self();
        $center->setChildren(self::normalizeChildren($children));

        return $center;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('center'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
