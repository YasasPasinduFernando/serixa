<?php

declare(strict_types=1);

namespace Serixa\Admin\Support;

use Serixa\Admin\Theme\AdminTheme;
use Serixa\Theme\ThemeManager;

/**
 * Resolves the active theme wrapped in {@see AdminTheme}, so `admin.*`
 * component names always resolve regardless of which theme the host
 * application configured via {@see ThemeManager}.
 *
 * @internal
 */
trait ResolvesAdminTheme
{
    protected function theme(): AdminTheme
    {
        $current = ThemeManager::current();

        return $current instanceof AdminTheme ? $current : new AdminTheme($current);
    }
}
