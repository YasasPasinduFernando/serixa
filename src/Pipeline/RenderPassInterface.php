<?php

declare(strict_types=1);

namespace NovaPHP\Pipeline;

use NovaPHP\Runtime\RenderContext;

/**
 * One replaceable step in the render pipeline.
 */
interface RenderPassInterface
{
    public function name(): string;

    public function handle(RenderContext $context): void;
}
