<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Contracts\WidgetInterface;
use Serixa\DevTools\Contracts\InspectorInterface;
use Serixa\Rendering\Renderer;
use Serixa\Tree\TreeNode;

final class WidgetTreeInspector implements InspectorInterface
{
    public function __construct(
        private readonly DefaultWidgetInspector $widgetInspector = new DefaultWidgetInspector(),
        private readonly Renderer $renderer = new Renderer(),
    ) {
    }

    public function name(): string
    {
        return 'widget_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        if (! $subject instanceof WidgetInterface) {
            return ['tree' => null];
        }

        $tree = $this->renderer->tree($subject);

        return [
            'tree' => $this->nodeToArray($tree->root()),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function nodeToArray(TreeNode $node): array
    {
        if (! $node->isWidget()) {
            return [
                'type' => 'text',
                'value' => mb_substr((string) $node->text(), 0, 80),
                'children' => array_map(fn (TreeNode $child): array => $this->nodeToArray($child), $node->children()),
            ];
        }

        $snapshot = $this->widgetInspector->inspect($node->widget());

        return [
            'type' => 'widget',
            'class' => $snapshot->className(),
            'id' => $snapshot->id(),
            'attributes' => $snapshot->attributes(),
            'children' => array_map(fn (TreeNode $child): array => $this->nodeToArray($child), $node->children()),
        ];
    }
}
