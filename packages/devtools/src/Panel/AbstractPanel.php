<?php

declare(strict_types=1);

namespace Serixa\DevTools\Panel;

use Serixa\DevTools\Contracts\PanelInterface;
use Serixa\DevTools\Support\Html;
use Serixa\DevTools\Support\ResolvesDevToolsTheme;
use Serixa\Support\ClassMerger;

abstract class AbstractPanel implements PanelInterface
{
    use ResolvesDevToolsTheme;

    private static ClassMerger $merger;

    public function __construct(
        private readonly string $name,
        private readonly string $label,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function label(): string
    {
        return $this->label;
    }

    /**
     * @param array<string, mixed> $data
     */
    abstract public function render(array $data): string;

    /**
     * @param list<string> $classes
     */
    protected function classString(array $classes): string
    {
        self::$merger ??= new ClassMerger();

        return self::$merger->toString($classes, []);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function section(string $heading, string $body, array $data): string
    {
        return Html::tag(
            'section',
            sprintf(' class="devtools-panel-body" data-devtools-panel="%s"', Html::escape($this->name())),
            Html::tag('h3', ' class="font-semibold mb-2"', Html::escape($heading)) . $body,
        );
    }
}
