<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use Serixa\Contracts\WidgetInterface;
use Serixa\DevTools\Contracts\InspectorInterface;

/**
 * Focuses on widgets under the Serixa\Component namespace.
 */
final class ComponentTreeInspector implements InspectorInterface
{
    public function __construct(
        private readonly WidgetTreeInspector $widgetTreeInspector = new WidgetTreeInspector(),
    ) {
    }

    public function name(): string
    {
        return 'component_tree';
    }

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array
    {
        if (! $subject instanceof WidgetInterface) {
            return ['components' => []];
        }

        $tree = $this->widgetTreeInspector->inspect($subject)['tree'] ?? null;

        if (! is_array($tree)) {
            return ['components' => []];
        }

        return [
            'components' => $this->filterComponents($tree),
        ];
    }

    /**
     * @param array<string, mixed> $node
     * @return list<array<string, mixed>>
     */
    private function filterComponents(array $node): array
    {
        $results = [];

        if (($node['type'] ?? '') === 'widget') {
            $class = (string) ($node['class'] ?? '');

            if (str_starts_with($class, 'Serixa\\Component\\')) {
                $results[] = [
                    'class' => $class,
                    'id' => $node['id'] ?? null,
                ];
            }
        }

        foreach ($node['children'] ?? [] as $child) {
            if (is_array($child)) {
                $results = array_merge($results, $this->filterComponents($child));
            }
        }

        return $results;
    }
}
