<?php

declare(strict_types=1);

namespace Serixa\Calendar\Support;

use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Contracts\ThemeInterface;
use Serixa\Theme\ThemeManager;

/**
 * Resolves the active theme wrapped as CalendarTheme(inner).
 *
 * @internal
 */
trait ResolvesCalendarTheme
{
    protected function theme(): CalendarTheme
    {
        $current = ThemeManager::current();

        if ($current instanceof CalendarTheme) {
            return $current;
        }

        return new CalendarTheme($current);
    }

    /**
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
