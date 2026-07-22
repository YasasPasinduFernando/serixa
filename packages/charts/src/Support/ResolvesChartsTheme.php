<?php

declare(strict_types=1);

namespace Serixa\Charts\Support;

use Serixa\Charts\Theme\ChartsTheme;
use Serixa\Contracts\ThemeInterface;
use Serixa\Theme\ThemeManager;

/**
 * Resolves the active theme wrapped as ChartsTheme(inner) so `charts.*` keys resolve.
 *
 * @internal
 */
trait ResolvesChartsTheme
{
    protected function theme(): ChartsTheme
    {
        $current = ThemeManager::current();

        if ($current instanceof ChartsTheme) {
            return $current;
        }

        return new ChartsTheme($current);
    }

    /**
     * Walk decorator `inner()` chains looking for a theme of the given class.
     *
     * @param class-string $themeClass
     */
    protected function containsTheme(ThemeInterface $theme, string $themeClass): bool
    {
        $current = $theme;

        while (true) {
            if ($current instanceof $themeClass) {
                return true;
            }

            if (! method_exists($current, 'inner')) {
                return false;
            }

            $next = $current->inner();

            if (! $next instanceof ThemeInterface) {
                return false;
            }

            $current = $next;
        }
    }
}
