<?php

declare(strict_types=1);

namespace Serixa\Admin\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Token\DesignToken;

/**
 * Decorates a host {@see ThemeInterface} with the `admin.*` component
 * vocabulary used by serixa/admin widgets.
 *
 * Component names starting with `admin.` are resolved by {@see AdminThemeMap}
 * (which itself borrows color/radius/shadow/typography tokens from the inner
 * theme where possible so admin widgets stay on-brand). Every other
 * component name is delegated to the inner theme untouched, so serixa/admin
 * can sit on top of the default theme or any custom {@see ThemeInterface}.
 *
 * @example ThemeManager::set(new AdminTheme(new DefaultTheme()))
 */
final class AdminTheme implements ThemeInterface
{
    public function __construct(
        private readonly ThemeInterface $inner,
    ) {
    }

    /**
     * The decorated (non-admin) theme this instance wraps.
     */
    public function inner(): ThemeInterface
    {
        return $this->inner;
    }

    public function classes(string $component, array $state = []): array
    {
        if (str_starts_with($component, 'admin.')) {
            return AdminThemeMap::classes($component, $state, $this->inner);
        }

        return $this->inner->classes($component, $state);
    }

    public function token(DesignToken|string $token): array
    {
        return $this->inner->token($token);
    }

    public function buttonPrimary(): array
    {
        return $this->inner->buttonPrimary();
    }

    public function buttonSecondary(): array
    {
        return $this->inner->buttonSecondary();
    }

    public function buttonDanger(): array
    {
        return $this->inner->buttonDanger();
    }

    public function buttonGhost(): array
    {
        return $this->inner->buttonGhost();
    }
}
