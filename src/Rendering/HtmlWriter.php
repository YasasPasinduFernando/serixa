<?php

declare(strict_types=1);

namespace NovaPHP\Rendering;

use NovaPHP\Runtime\RenderContext;

/**
 * Writes HTML fragments into RenderContext (separated from pipeline orchestration).
 */
final class HtmlWriter
{
    public function write(RenderContext $context, string $html): void
    {
        $context->appendHtml($html);
    }

    public function writeTrusted(RenderContext $context, string $html): void
    {
        $this->write($context, $html);
    }

    public function reset(RenderContext $context): void
    {
        $context->setHtml('');
    }
}
