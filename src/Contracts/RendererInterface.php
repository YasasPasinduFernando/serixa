<?php

declare(strict_types=1);

namespace NovaPHP\Contracts;

/**
 * Renders one or more widgets into a single HTML document fragment.
 */
interface RendererInterface
{
    /**
     * Render any number of widgets (or raw trusted HTML strings) into one string.
     *
     * @param WidgetInterface|string ...$nodes
     */
    public function render(WidgetInterface|string ...$nodes): string;
}
