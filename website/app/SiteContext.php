<?php

declare(strict_types=1);

namespace App;

/**
 * Per-request website context (theme + active page).
 */
final class SiteContext
{
    private static string $theme = 'light';

    private static string $page = 'home';

    private function __construct()
    {
    }

    public static function boot(string $page, string $theme): void
    {
        self::$page = $page;
        self::$theme = $theme === 'dark' ? 'dark' : 'light';
    }

    public static function theme(): string
    {
        return self::$theme;
    }

    public static function page(): string
    {
        return self::$page;
    }

    public static function isDark(): bool
    {
        return self::$theme === 'dark';
    }
}
