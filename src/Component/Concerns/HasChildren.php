<?php

declare(strict_types=1);

namespace NovaPHP\Component\Concerns;

use NovaPHP\Contracts\WidgetInterface;

/**
 * Shared child-list management for composable layout widgets.
 */
trait HasChildren
{
    /** @var list<WidgetInterface|string> */
    private array $children = [];

    /**
     * @param list<WidgetInterface|string>|WidgetInterface|string ...$nodes
     * @return list<WidgetInterface|string>
     */
    protected static function normalizeChildren(array $nodes): array
    {
        if (count($nodes) === 1 && is_array($nodes[0])) {
            /** @var list<WidgetInterface|string> $nodes */
            $nodes = $nodes[0];
        }

        $children = [];

        foreach ($nodes as $node) {
            if (! $node instanceof WidgetInterface && ! is_string($node)) {
                throw new \InvalidArgumentException('Children must be widgets or strings.');
            }

            $children[] = $node;
        }

        return $children;
    }

    /**
     * @param list<WidgetInterface|string> $children
     */
    protected function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * @return static
     */
    public function child(WidgetInterface|string $child): static
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param list<WidgetInterface|string> $children
     * @return static
     */
    public function children(array $children): static
    {
        foreach ($children as $child) {
            $this->child($child);
        }

        return $this;
    }

    /**
     * @return list<WidgetInterface|string>
     */
    protected function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return list<WidgetInterface|string>
     */
    public function childrenForTree(): array
    {
        return $this->getChildren();
    }
}
