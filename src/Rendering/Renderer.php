<?php

declare(strict_types=1);

namespace NovaPHP\Rendering;

use NovaPHP\Contracts\RendererInterface;
use NovaPHP\Contracts\WidgetInterface;
use NovaPHP\Tree\LifecycleTreeVisitor;
use NovaPHP\Tree\TreeWalker;
use NovaPHP\Tree\WidgetTree;

/**
 * Renders widgets through WidgetTree with lifecycle hooks.
 *
 * Does not wrap in html/body — that belongs to a future Document layer.
 * Raw strings passed here are treated as trusted HTML (use widgets for user text).
 */
final class Renderer implements RendererInterface
{
    public function __construct(
        private readonly TreeWalker $walker = new TreeWalker(),
    ) {
    }

    public function render(WidgetInterface|string ...$nodes): string
    {
        $html = '';

        foreach ($nodes as $node) {
            $html .= is_string($node)
                ? $node
                : $this->renderWidget($node);
        }

        return $html;
    }

    /**
     * Render a single widget (alias retained for Milestone 1 callers).
     */
    public function renderComponent(WidgetInterface $component): string
    {
        return $this->renderWidget($component);
    }

    /**
     * Render a single widget through the tree + lifecycle pipeline.
     */
    public function renderWidget(WidgetInterface $widget): string
    {
        $tree = WidgetTree::from($widget);

        $this->walker->walk($tree->root(), LifecycleTreeVisitor::mount());
        $this->walker->walk($tree->root(), LifecycleTreeVisitor::beforeRender());

        $html = $widget->render();

        $this->walker->walk($tree->root(), LifecycleTreeVisitor::afterRender());

        return $html;
    }

    /**
     * Build a WidgetTree without rendering (inspection / DevTools).
     */
    public function tree(WidgetInterface $widget): WidgetTree
    {
        return WidgetTree::from($widget);
    }
}
