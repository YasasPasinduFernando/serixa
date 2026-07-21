<?php

declare(strict_types=1);

namespace NovaPHP\Theme;

use NovaPHP\Contracts\ThemeInterface;

/**
 * Process-wide theme accessor.
 *
 * Convention: DefaultTheme until an app calls set().
 * Reset exists for isolated tests — not for application hot-swapping mid-request.
 */
final class ThemeManager
{
    private static ?ThemeInterface $theme = null;

    private function __construct()
    {
    }

    public static function set(ThemeInterface $theme): void
    {
        self::$theme = $theme;
    }

    public static function current(): ThemeInterface
    {
        return self::$theme ??= new DefaultTheme();
    }

    public static function reset(): void
    {
        self::$theme = null;
    }
}
