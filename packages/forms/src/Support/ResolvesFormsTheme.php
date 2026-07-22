<?php

declare(strict_types=1);

namespace Serixa\Forms\Support;

use Serixa\Forms\Theme\FormsTheme;
use Serixa\Theme\ThemeManager;

/**
 * Resolves the active theme wrapped in {@see FormsTheme}, so `forms.*`
 * component names always resolve regardless of which theme the host
 * application configured via {@see ThemeManager}.
 *
 * @internal
 */
trait ResolvesFormsTheme
{
    protected function theme(): FormsTheme
    {
        $current = ThemeManager::current();

        return $current instanceof FormsTheme ? $current : new FormsTheme($current);
    }
}
