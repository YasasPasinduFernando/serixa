<?php

declare(strict_types=1);

namespace Serixa\DevTools\Inspector;

use ReflectionClass;
use Serixa\Contracts\WidgetInterface;
use Serixa\Inspector\WidgetInspector;
use Serixa\Inspector\WidgetSnapshot;

final class DefaultWidgetInspector implements WidgetInspector
{
    public function inspect(WidgetInterface $widget): WidgetSnapshot
    {
        $className = $widget::class;
        $id = $this->resolveId($widget);
        $attributes = $this->resolveAttributes($widget);

        $children = [];
        foreach ($widget->childrenForTree() as $child) {
            $children[] = $child instanceof WidgetInterface ? $child::class : '(text)';
        }

        return new WidgetSnapshot($className, $id, $attributes, $children);
    }

    private function resolveId(WidgetInterface $widget): ?string
    {
        if (method_exists($widget, 'id') && is_callable([$widget, 'id'])) {
            $ref = new ReflectionClass($widget);

            if ($ref->hasProperty('id')) {
                $prop = $ref->getProperty('id');
                $prop->setAccessible(true);
                $value = $prop->getValue($widget);

                if (is_string($value) && $value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveAttributes(WidgetInterface $widget): array
    {
        if (method_exists($widget, 'toArray') && is_callable([$widget, 'toArray'])) {
            /** @var mixed $result */
            $result = $widget->toArray();

            if (is_array($result)) {
                return $result;
            }
        }

        return [];
    }
}
