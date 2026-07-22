<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Inspector\RenderSnapshot;
use Serixa\Inspector\TreeInspector;
use Serixa\Inspector\WidgetSnapshot;
use Serixa\Tree\TreeNode;
use Serixa\Tree\WidgetTree;

final class DefaultTreeInspector implements TreeInspector
{
    public function __construct(
        private readonly DefaultWidgetInspector $widgetInspector = new DefaultWidgetInspector(),
    ) {
    }

    public function inspect(WidgetTree $tree): RenderSnapshot
    {
        $widgets = [];
        $nodeCount = $this->walk($tree->root(), $widgets);
        $widget = $tree->root()->widget();
        $renderId = 'render_' . substr(sha1($widget !== null ? $widget::class : 'text'), 0, 12);

        return new RenderSnapshot(
            $renderId,
            $widgets,
            ['source' => 'DefaultTreeInspector'],
            $nodeCount,
        );
    }

    /**
     * @param list<WidgetSnapshot> $widgets
     */
    private function walk(TreeNode $node, array &$widgets): int
    {
        $count = 1;

        if ($node->isWidget()) {
            $widgets[] = $this->widgetInspector->inspect($node->widget());
        }

        foreach ($node->children() as $child) {
            $count += $this->walk($child, $widgets);
        }

        return $count;
    }
}
