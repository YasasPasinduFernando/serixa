<?php

declare(strict_types=1);

namespace NovaPHP\Plugin;

use NovaPHP\Container\Container;
use NovaPHP\Registry\WidgetRegistry;

/**
 * Base plugin contract — boots into the runtime container and registry.
 */
interface PluginInterface
{
    public function name(): string;

    public function boot(Container $container, WidgetRegistry $registry): void;
}
