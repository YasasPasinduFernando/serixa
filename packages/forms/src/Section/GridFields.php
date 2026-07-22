<?php

declare(strict_types=1);

namespace Serixa\Forms\Section;

use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Responsive grid layout for related fields.
 */
final class GridFields extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    /** @var list<WidgetInterface|string> */
    private array $gridChildren = [];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $grid = new self();
        $grid->gridChildren = self::normalize($children);

        return $grid;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public function children(WidgetInterface|string|array ...$children): self
    {
        $this->gridChildren = self::normalize($children);

        return $this;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString($this->theme()->classes('forms.grid'));

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->gridChildren));
    }

    /**
     * @param array<int, mixed> $nodes
     * @return list<WidgetInterface|string>
     */
    private static function normalize(array $nodes): array
    {
        if (count($nodes) === 1 && is_array($nodes[0])) {
            /** @var array<int, mixed> $nodes */
            $nodes = $nodes[0];
        }

        $children = [];

        foreach ($nodes as $node) {
            if (! $node instanceof WidgetInterface && ! is_string($node)) {
                throw new InvalidArgumentException(sprintf(
                    'Children must be widgets or strings; %s given.',
                    get_debug_type($node),
                ));
            }

            $children[] = $node;
        }

        return $children;
    }
}
