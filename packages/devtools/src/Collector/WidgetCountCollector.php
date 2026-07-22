<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Support\CollectorContext;
use Serixa\Rendering\Renderer;
use Serixa\Tree\TreeNode;

final class WidgetCountCollector extends AbstractCollector
{
    public function __construct(
        private readonly Renderer $renderer = new Renderer(),
    ) {
        parent::__construct('widget_count');
    }

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array
    {
        $widget = $ctx->widget();

        if ($widget === null) {
            return ['count' => 0, 'textNodes' => 0];
        }

        $tree = $this->renderer->tree($widget);
        $counts = $this->countNodes($tree->root());

        return [
            'count' => $counts['widgets'],
            'textNodes' => $counts['text'],
        ];
    }

    /**
     * @return array{widgets: int, text: int}
     */
    private function countNodes(TreeNode $node): array
    {
        $widgets = $node->isWidget() ? 1 : 0;
        $text = $node->isWidget() ? 0 : 1;

        foreach ($node->children() as $child) {
            $childCounts = $this->countNodes($child);
            $widgets += $childCounts['widgets'];
            $text += $childCounts['text'];
        }

        return ['widgets' => $widgets, 'text' => $text];
    }
}
