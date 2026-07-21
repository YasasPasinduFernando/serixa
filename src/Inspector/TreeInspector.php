<?php

declare(strict_types=1);

namespace NovaPHP\Inspector;

use NovaPHP\Tree\WidgetTree;

/**
 * Contract for inspecting a widget tree (DevTools — no UI).
 */
interface TreeInspector
{
    public function inspect(WidgetTree $tree): RenderSnapshot;
}
