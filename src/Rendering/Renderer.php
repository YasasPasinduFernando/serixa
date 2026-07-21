<?php

declare(strict_types=1);

namespace NovaPHP\Rendering;

use NovaPHP\Contracts\RendererInterface;
use NovaPHP\Contracts\WidgetInterface;
use NovaPHP\Pipeline\RenderPipeline;
use NovaPHP\Runtime\RenderContext;
use NovaPHP\Runtime\WidgetContext;
use NovaPHP\Tree\WidgetTree;

/**
 * Render coordinator: builds a RenderContext and runs the replaceable pipeline.
 *
 * Public API unchanged from Milestone 1–3. Pipeline is an additive extension point.
 */
final class Renderer implements RendererInterface
{
    private readonly RenderPipeline $pipeline;

    public function __construct(
        ?RenderPipeline $pipeline = null,
        private readonly ?WidgetContext $defaultWidgetContext = null,
    ) {
        $this->pipeline = $pipeline ?? RenderPipeline::default();
    }

    public static function withPipeline(RenderPipeline $pipeline, ?WidgetContext $context = null): self
    {
        return new self($pipeline, $context);
    }

    public function render(WidgetInterface|string ...$nodes): string
    {
        $buffer = '';

        foreach ($nodes as $node) {
            $buffer .= is_string($node)
                ? $node
                : $this->renderWidget($node);
        }

        return $buffer;
    }

    /**
     * Render a single widget (alias retained for Milestone 1 callers).
     */
    public function renderComponent(WidgetInterface $component): string
    {
        return $this->renderWidget($component);
    }

    /**
     * Render a single widget through the runtime pipeline.
     */
    public function renderWidget(WidgetInterface $widget, ?RenderContext $context = null): string
    {
        $context ??= new RenderContext(
            widgetContext: $this->defaultWidgetContext ?? WidgetContext::defaults(),
        );
        $context->setRoot($widget);
        $context->setHtml('');

        $this->pipeline->run($context);

        return $context->html();
    }

    /**
     * Build a WidgetTree without rendering (inspection / DevTools).
     */
    public function tree(WidgetInterface $widget): WidgetTree
    {
        return WidgetTree::from($widget);
    }

    public function pipeline(): RenderPipeline
    {
        return $this->pipeline;
    }
}
