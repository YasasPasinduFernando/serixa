<?php

declare(strict_types=1);

namespace NovaPHP\Pipeline\Pass;

use NovaPHP\Pipeline\RenderPassInterface;
use NovaPHP\Runtime\RenderContext;
use NovaPHP\Theme\ThemeManager;

/**
 * Bridges WidgetContext theme into ThemeManager for BC with existing widgets.
 */
final class ThemeResolutionPass implements RenderPassInterface
{
    public function name(): string
    {
        return 'theme_resolution';
    }

    public function handle(RenderContext $context): void
    {
        ThemeManager::set($context->widgetContext()->theme());
        $context->setMeta('pipeline.theme_resolved', true);
    }
}
