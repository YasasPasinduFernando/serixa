<?php

declare(strict_types=1);

namespace NovaPHP\Component;

use NovaPHP\Component\Concerns\HasChildren;
use NovaPHP\Component\Concerns\ResolvesTheme;
use NovaPHP\Contracts\ComponentInterface;

/**
 * Spacing wrapper. Prefer static factories over raw Tailwind padding classes.
 *
 * @example Padding::all(4)->child(Text::make('Inset'))
 */
final class Padding extends Component
{
    use HasChildren;
    use ResolvesTheme;

    /** @var array<string, int|float|string|null> */
    private array $sides = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(ComponentInterface|string|array ...$children): self
    {
        $padding = new self();
        $padding->setChildren(self::normalizeChildren($children));

        return $padding;
    }

    public static function all(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('all', $value, $children);
    }

    public static function x(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('x', $value, $children);
    }

    public static function y(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('y', $value, $children);
    }

    public static function top(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('top', $value, $children);
    }

    public static function bottom(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('bottom', $value, $children);
    }

    public static function left(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('left', $value, $children);
    }

    public static function right(int|float|string $value, ComponentInterface|string|array ...$children): self
    {
        return self::factory('right', $value, $children);
    }

    /**
     * @param list<ComponentInterface|string|array> $children
     */
    private static function factory(string $side, int|float|string $value, array $children): self
    {
        $padding = new self();
        $padding->sides[$side] = $value;
        $padding->setChildren(self::normalizeChildren($children));

        return $padding;
    }

    public function render(): string
    {
        $attrs = $this->buildAttributeString(
            $this->theme()->classes('padding', $this->sides),
        );

        return sprintf('<div%s>%s</div>', $attrs, $this->renderChildren($this->getChildren()));
    }
}
