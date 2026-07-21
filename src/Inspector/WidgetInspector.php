<?php

declare(strict_types=1);

namespace NovaPHP\Inspector;

use NovaPHP\Contracts\WidgetInterface;

/**
 * Contract for inspecting a single widget instance (DevTools — no UI).
 */
interface WidgetInspector
{
    public function inspect(WidgetInterface $widget): WidgetSnapshot;
}
