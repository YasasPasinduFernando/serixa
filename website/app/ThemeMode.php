<?php

declare(strict_types=1);

namespace App;

/**
 * Resolves light/dark color mode from cookie / query for the request.
 */
final class ThemeMode
{
    public const COOKIE = 'serixa_theme';

    private function __construct()
    {
    }

    public static function resolve(?string $queryTheme, ?string $cookieTheme): string
    {
        $candidate = $queryTheme ?? $cookieTheme ?? 'light';

        return $candidate === 'dark' ? 'dark' : 'light';
    }

    /**
     * @param array<string, scalar|null> $query
     */
    public static function toggleUrl(string $current, string $page, array $query = []): string
    {
        $next = $current === 'dark' ? 'light' : 'dark';
        $params = array_merge($query, ['page' => $page, 'theme' => $next]);

        return '?' . http_build_query($params);
    }
}
