<?php

declare(strict_types=1);

namespace NovaPHP\Pipeline\Pass;

use NovaPHP\Pipeline\RenderPassInterface;
use NovaPHP\Rendering\HtmlWriter;
use NovaPHP\Runtime\RenderContext;
use NovaPHP\Tree\LifecycleTreeVisitor;
use NovaPHP\Tree\TreeWalker;

/**
 * Invokes widget render() and writes HTML via HtmlWriter; finishes lifecycle.
 */
final class RenderPass implements RenderPassInterface
{
    public function __construct(
        private readonly HtmlWriter $writer = new HtmlWriter(),
        private readonly TreeWalker $walker = new TreeWalker(),
    ) {
    }

    public function name(): string
    {
        return 'render';
    }

    public function handle(RenderContext $context): void
    {
        $root = $context->root();

        if ($root === null) {
            throw new \RuntimeException('RenderPass requires a root widget.');
        }

        $fragment = $root->render();
        $this->writer->write($context, $fragment);

        $tree = $context->tree();

        if ($tree !== null) {
            $this->walker->walk($tree->root(), LifecycleTreeVisitor::afterRender());
            $context->setMeta('lifecycle.phase', 'done');
        }

        $context->setMeta('pipeline.rendered', true);
    }
}
