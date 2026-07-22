<?php

declare(strict_types=1);

namespace Serixa\DevTools\Runtime;

use Serixa\Contracts\WidgetInterface;
use Serixa\DevTools\Inspector\DefaultWidgetInspector;
use Serixa\Rendering\Renderer;
use Serixa\Tree\TreeNode;

/**
 * Builds element-inspection payloads for the DevTools client runtime.
 */
final class ElementInspector
{
    public function __construct(
        private readonly DefaultWidgetInspector $widgetInspector = new DefaultWidgetInspector(),
        private readonly Renderer $renderer = new Renderer(),
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function buildPayload(?WidgetInterface $widget): array
    {
        if ($widget === null) {
            return ['widgets' => []];
        }

        $tree = $this->renderer->tree($widget);
        $widgets = [];
        $this->collectSnapshots($tree->root(), $widgets);

        return [
            'widgets' => array_map(
                static fn ($snapshot): array => $snapshot->toArray(),
                $widgets,
            ),
        ];
    }

    /**
     * @param list<\Serixa\Inspector\WidgetSnapshot> $widgets
     */
    private function collectSnapshots(TreeNode $node, array &$widgets): void
    {
        if ($node->isWidget()) {
            $widgets[] = $this->widgetInspector->inspect($node->widget());
        }

        foreach ($node->children() as $child) {
            $this->collectSnapshots($child, $widgets);
        }
    }
}
