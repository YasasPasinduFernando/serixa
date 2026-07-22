<?php

declare(strict_types=1);

namespace Serixa\Forms\Section;

use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Forms\Contracts\FormsWidgetInterface;
use Serixa\Forms\Support\ResolvesFormsTheme;

/**
 * Titled form section grouping related fields.
 */
final class FormSection extends Component implements FormsWidgetInterface
{
    use ResolvesFormsTheme;

    private string $title;

    private ?string $descriptionText = null;

    /** @var list<WidgetInterface|string> */
    private array $sectionChildren = [];

    private function __construct(string $title)
    {
        parent::__construct();
        $this->title = $title;
    }

    public static function make(string $title = ''): self
    {
        return new self($title);
    }

    public function description(string $description): self
    {
        $this->descriptionText = $description;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public function children(WidgetInterface|string|array ...$children): self
    {
        $this->sectionChildren = self::normalize($children);

        return $this;
    }

    public function child(WidgetInterface|string $child): self
    {
        $this->sectionChildren[] = $child;

        return $this;
    }

    public function render(): string
    {
        $header = '';

        if ($this->title !== '') {
            $titleAttrs = $this->buildAttributeString($this->theme()->classes('forms.section.title'));
            $header .= sprintf('<h2%s>%s</h2>', $titleAttrs, $this->escape($this->title));
        }

        if ($this->descriptionText !== null) {
            $descAttrs = $this->buildAttributeString($this->theme()->classes('forms.section.description'));
            $header .= sprintf('<p%s>%s</p>', $descAttrs, $this->escape($this->descriptionText));
        }

        $body = $this->renderChildren($this->sectionChildren);

        $attrs = $this->buildAttributeString($this->theme()->classes('forms.section'));

        return sprintf('<section%s>%s%s</section>', $attrs, $header, $body);
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
