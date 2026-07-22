<?php

declare(strict_types=1);

namespace Serixa\Charts\Axis;

/**
 * Vertical axis configuration.
 */
final class YAxis extends Axis
{
    public static function make(): self
    {
        return new self();
    }
}
