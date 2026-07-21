<?php

declare(strict_types=1);

namespace NovaPHP\Component\Concerns;

use NovaPHP\Contracts\ThemeInterface;
use NovaPHP\Theme\ThemeManager;

/**
 * Resolves the active theme for class lookup.
 *
 * @internal
 */
trait ResolvesTheme
{
    protected function theme(): ThemeInterface
    {
        return ThemeManager::current();
    }
}
