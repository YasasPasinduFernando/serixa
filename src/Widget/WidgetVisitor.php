<?php

declare(strict_types=1);

namespace NovaPHP\Widget;

use NovaPHP\Contracts\WidgetInterface;

/**
 * Visitor over live widgets (preparation for DevTools / plugins).
 */
interface WidgetVisitor
{
    public function visit(WidgetInterface $widget): void;
}
