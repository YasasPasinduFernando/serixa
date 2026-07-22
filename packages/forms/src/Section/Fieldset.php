<?php

declare(strict_types=1);

namespace Serixa\Forms\Section;

use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Semantic fieldset with optional legend.
 */
final class Fieldset extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    private ?Legend $legendWidget = null;

    private ?string $legendText = null;

    /** @var list<WidgetInterface|string> */
    private array $fieldsetChildren = [];

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $fieldset = new self();
        $fieldset->fieldsetChildren = self::normalize($children);

        return $fieldset;
    }

    public function legend(string|Legend $legend): self
    {
        if ($legend instanceof Legend) {
            $this->legendWidget = $legend;
            $this->legendText = null;
        } else {
            $this->legendText = $legend;
            $this->legendWidget = null;
        }

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public function children(WidgetInterface|string|array ...$children): self
    {
        $this->fieldsetChildren = self::normalize($children);

        return $this;
    }

    public function render(): string
    {
        $inner = '';

        if ($this->legendWidget !== null) {
            $inner .= $this->legendWidget->render();
        } elseif ($this->legendText !== null) {
            $inner .= Legend::make($this->legendText)->render();
        }

        $inner .= $this->renderChildren($this->fieldsetChildren);

        $attrs = $this->buildAttributeString($this->theme()->classes('forms.fieldset'));

        return sprintf('<fieldset%s>%s</fieldset>', $attrs, $inner);
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
