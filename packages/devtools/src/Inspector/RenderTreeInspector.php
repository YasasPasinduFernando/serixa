<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Contracts\WidgetInterface;
use Serixa\DevTools\Contracts\InspectorInterface;
use Serixa\Rendering\Renderer;

final class RenderTreeInspector implements InspectorInterface
{
    public function __construct(
        private readonly DefaultTreeInspector $treeInspector = new DefaultTreeInspector(),
        private readonly Renderer $renderer = new Renderer(),
    ) {
    }

    public function name(): string
    {
        return 'render_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        if (! $subject instanceof WidgetInterface) {
            return ['snapshot' => null];
        }

        $tree = $this->renderer->tree($subject);
        $snapshot = $this->treeInspector->inspect($tree);

        return [
            'snapshot' => $snapshot->toArray(),
            'renderId' => $snapshot->renderId(),
            'nodeCount' => $snapshot->nodeCount(),
        ];
    }
}
