<?php

declare(strict_types=1);

namespace Serixa\Registry;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Center;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Divider;
use Serixa\Component\Expanded;
use Serixa\Component\Padding;
use Serixa\Component\Row;
use Serixa\Component\Scaffold;
use Serixa\Component\SizedBox;
use Serixa\Component\Spacer;
use Serixa\Component\Stack;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Exception\RegistryException;

/**
 * Registers widget types and their metadata for discovery.
 */
final class WidgetRegistry
{
    /** @var array<string, WidgetMetadata> */
    private array $widgets = [];

    public function register(WidgetMetadata $metadata): void
    {
        if ($metadata->name() === '') {
            throw new RegistryException('Widget metadata name cannot be empty.');
        }

        $this->widgets[$metadata->name()] = $metadata;
    }

    public function unregister(string $name): void
    {
        unset($this->widgets[$name]);
    }

    public function find(string $name): ?WidgetMetadata
    {
        return $this->widgets[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->widgets[$name]);
    }

    /**
     * @return array<string, WidgetMetadata>
     */
    public function all(): array
    {
        return $this->widgets;
    }

    /**
     * @return class-string<WidgetInterface>|null
     */
    public function classFor(string $name): ?string
    {
        $meta = $this->find($name);

        if ($meta === null || $meta->className() === '') {
            return null;
        }

        /** @var class-string<WidgetInterface> $class */
        $class = $meta->className();

        return $class;
    }

    /**
     * Seed registry with built-in SERIXA widgets.
     */
    public static function withDefaults(): self
    {
        $registry = new self();

        foreach (self::defaultMetadata() as $metadata) {
            $registry->register($metadata);
        }

        return $registry;
    }

    /**
     * @return list<WidgetMetadata>
     */
    public static function defaultMetadata(): array
    {
        return [
            new WidgetMetadata('button', 'input', 'Interactive button', ['variant', 'size', 'disabled', 'rounded'], ['click'], false, '0.1.0', Button::class),
            new WidgetMetadata('text', 'typography', 'Semantic text node', ['size', 'weight', 'tone', 'as'], [], false, '0.1.0', Text::class),
            new WidgetMetadata('card', 'surface', 'Content card', ['title', 'padding'], [], true, '0.1.0', Card::class),
            new WidgetMetadata('container', 'layout', 'Page width container', ['maxWidth', 'padding'], [], true, '0.1.0', Container::class),
            new WidgetMetadata('row', 'layout', 'Horizontal flex row', ['justify', 'align', 'gap'], [], true, '0.2.0', Row::class),
            new WidgetMetadata('column', 'layout', 'Vertical flex column', ['align', 'gap'], [], true, '0.2.0', Column::class),
            new WidgetMetadata('center', 'layout', 'Centers children', [], [], true, '0.2.0', Center::class),
            new WidgetMetadata('padding', 'layout', 'Padding wrapper', ['all', 'x', 'y'], [], true, '0.2.0', Padding::class),
            new WidgetMetadata('spacer', 'layout', 'Flex spacer', [], [], false, '0.2.0', Spacer::class),
            new WidgetMetadata('expanded', 'layout', 'Flex grow wrapper', [], [], true, '0.2.0', Expanded::class),
            new WidgetMetadata('sizedBox', 'layout', 'Fixed width/height box', ['width', 'height'], [], true, '0.2.0', SizedBox::class),
            new WidgetMetadata('stack', 'layout', 'Layered stack', [], [], true, '0.2.0', Stack::class),
            new WidgetMetadata('divider', 'layout', 'Horizontal rule', [], [], false, '0.2.0', Divider::class),
            new WidgetMetadata('scaffold', 'layout', 'App shell (appBar, sidebar, body, footer)', ['appBar', 'sidebar', 'body', 'footer'], [], true, '0.5.2', Scaffold::class),
        ];
    }
}
