<?php

declare(strict_types=1);

namespace Serixa\Charts\Axis;

/**
 * Horizontal axis configuration.
 */
final class XAxis extends Axis
{
    public static function make(): self
    {
        return new self();
    }
}
