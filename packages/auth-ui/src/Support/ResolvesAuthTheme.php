<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Support;

use Serixa\Admin\Theme\AdminTheme;
use Serixa\AuthUi\Theme\AuthTheme;
use Serixa\Contracts\ThemeInterface;
use Serixa\Forms\Theme\FormsTheme;
use Serixa\Theme\ThemeManager;

/**
 * Resolves the active theme wrapped as AuthTheme(FormsTheme(AdminTheme(inner))),
 * so `auth.*` (and nested forms/admin) component names always resolve.
 *
 * @internal
 */
trait ResolvesAuthTheme
{
    protected function theme(): AuthTheme
    {
        $current = ThemeManager::current();

        if ($current instanceof AuthTheme) {
            return $current;
        }

        $inner = $current;

        if (! $this->containsTheme($inner, AdminTheme::class)) {
            $inner = new AdminTheme($inner);
        }

        if (! $this->containsTheme($inner, FormsTheme::class)) {
            $inner = new FormsTheme($inner);
        }

        return new AuthTheme($inner);
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
