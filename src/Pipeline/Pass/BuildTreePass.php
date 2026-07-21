<?php

declare(strict_types=1);

namespace NovaPHP\Pipeline\Pass;

use NovaPHP\Pipeline\RenderPassInterface;
use NovaPHP\Runtime\RenderContext;
use NovaPHP\Tree\WidgetTree;

/**
 * Builds WidgetTree from the root widget.
 */
final class BuildTreePass implements RenderPassInterface
{
    public function name(): string
    {
        return 'build_tree';
    }

    public function handle(RenderContext $context): void
    {
        $root = $context->root();

        if ($root === null) {
            throw new \RuntimeException('RenderContext has no root widget.');
        }

        $context->setTree(WidgetTree::from($root));
        $context->setMeta('pipeline.tree_built', true);
    }
}
