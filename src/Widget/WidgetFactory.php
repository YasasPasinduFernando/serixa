<?php

declare(strict_types=1);

namespace NovaPHP\Widget;

use NovaPHP\Component\Button;
use NovaPHP\Component\Card;
use NovaPHP\Component\Center;
use NovaPHP\Component\Column;
use NovaPHP\Component\Container;
use NovaPHP\Component\Divider;
use NovaPHP\Component\Expanded;
use NovaPHP\Component\Padding;
use NovaPHP\Component\Row;
use NovaPHP\Component\SizedBox;
use NovaPHP\Component\Spacer;
use NovaPHP\Component\Stack;
use NovaPHP\Component\Text;
use NovaPHP\Contracts\WidgetInterface;
use NovaPHP\Exception\InvalidArgumentException;

/**
 * Named widget construction for registries, serializers, and future DevTools.
 *
 * Does not replace fluent make() APIs — those remain the primary DX.
 */
final class WidgetFactory
{
    /** @var array<string, class-string<WidgetInterface>> */
    private array $map;

    /**
     * @param array<string, class-string<WidgetInterface>> $map
     */
    public function __construct(array $map = [])
    {
        $this->map = $map === [] ? self::defaults() : $map;
    }

    /**
     * @return array<string, class-string<WidgetInterface>>
     */
    public static function defaults(): array
    {
        return [
            'button' => Button::class,
            'card' => Card::class,
            'text' => Text::class,
            'container' => Container::class,
            'row' => Row::class,
            'column' => Column::class,
            'center' => Center::class,
            'padding' => Padding::class,
            'spacer' => Spacer::class,
            'expanded' => Expanded::class,
            'sizedBox' => SizedBox::class,
            'stack' => Stack::class,
            'divider' => Divider::class,
        ];
    }

    /**
     * @param class-string<WidgetInterface> $class
     */
    public function register(string $name, string $class): self
    {
        $this->map[$name] = $class;

        return $this;
    }

    /**
     * Create a widget by registry name. Pass constructor args for make()-style classes.
     *
     * @param mixed ...$args
     */
    public function make(string $name, mixed ...$args): WidgetInterface
    {
        if (! isset($this->map[$name])) {
            throw new InvalidArgumentException(sprintf('Unknown widget "%s".', $name));
        }

        $class = $this->map[$name];

        if (! method_exists($class, 'make')) {
            throw new InvalidArgumentException(sprintf('Widget "%s" has no make() factory.', $name));
        }

        /** @var WidgetInterface $widget */
        $widget = $class::make(...$args);

        return $widget;
    }

    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }
}
