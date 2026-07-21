<?php

declare(strict_types=1);

namespace NovaPHP\Pipeline\Pass;

use NovaPHP\Exception\PipelineException;
use NovaPHP\Pipeline\RenderPassInterface;
use NovaPHP\Runtime\RenderContext;
use NovaPHP\Tree\LifecycleTreeVisitor;
use NovaPHP\Tree\TreeWalker;

/**
 * Runs mount + beforeRender lifecycle hooks on the widget tree.
 * afterRender is applied in RenderPass after HTML is produced.
 *
 * @internal
 */
final class LifecyclePass implements RenderPassInterface
{
    public function __construct(
        private readonly TreeWalker $walker = new TreeWalker(),
    ) {
    }

    public function name(): string
    {
        return 'lifecycle';
    }

    public function handle(RenderContext $context): void
    {
        $tree = $context->tree();

        if ($tree === null) {
            throw new PipelineException('LifecyclePass requires a built WidgetTree.');
        }

        $this->walker->walk($tree->root(), LifecycleTreeVisitor::mount());
        $this->walker->walk($tree->root(), LifecycleTreeVisitor::beforeRender());
        $context->setMeta('pipeline.lifecycle', 'before');
    }
}
