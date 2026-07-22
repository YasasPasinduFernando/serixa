<?php

declare(strict_types=1);

namespace Serixa\DevTools\Support;

use Serixa\Contracts\ThemeInterface;
use Serixa\DevTools\Theme\DevToolsTheme;
use Serixa\Theme\ThemeManager;

/**
 * Resolves the active theme wrapped as DevToolsTheme(inner) so `devtools.*` keys resolve.
 *
 * @internal
 */
trait ResolvesDevToolsTheme
{
    protected function theme(): DevToolsTheme
    {
        $current = ThemeManager::current();

        if ($current instanceof DevToolsTheme) {
            return $current;
        }

        return new DevToolsTheme($current);
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
