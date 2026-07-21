<?php

declare(strict_types=1);

namespace Serixa\Component;

use Serixa\Component\Concerns\HasChildren;
use Serixa\Component\Concerns\ResolvesTheme;
use Serixa\Contracts\WidgetInterface;

/**
 * Semantic HTML table root.
 *
 * @example Table::make(TableHead::make(...), TableBody::make(...))
 */
final class Table extends Component
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
        $table = new self();
        $table->setChildren(self::normalizeChildren($children));

        return $table;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('table'));

        return sprintf('<table%s>%s</table>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
