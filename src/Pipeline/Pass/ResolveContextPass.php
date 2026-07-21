<?php

declare(strict_types=1);

namespace NovaPHP\Pipeline\Pass;

use NovaPHP\Pipeline\RenderPassInterface;
use NovaPHP\Runtime\RenderContext;

/**
 * Records resolved WidgetContext onto render metadata for downstream passes.
 *
 * @internal
 */
final class ResolveContextPass implements RenderPassInterface
{
    public function name(): string
    {
        return 'resolve_context';
    }

    public function handle(RenderContext $context): void
    {
        $widgetContext = $context->widgetContext();

        $context->setMeta('context.locale', $widgetContext->locale());
        $context->setMeta('context.colorMode', $widgetContext->colorMode());
        $context->setMeta('context.breakpoint', $widgetContext->breakpoint());
        $context->setMeta('context.environment', $widgetContext->environment());
        $context->setMeta('context.textDirection', $widgetContext->textDirection());
        $context->setMeta('pipeline.context_resolved', true);
    }
}
