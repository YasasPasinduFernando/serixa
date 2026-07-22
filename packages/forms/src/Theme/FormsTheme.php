<?php

declare(strict_types=1);

namespace Serixa\Forms\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Token\DesignToken;

/**
 * Decorates a host {@see ThemeInterface} with the `forms.*` component
 * vocabulary used by serixa/forms widgets.
 *
 * Component names starting with `forms.` are resolved by {@see FormsThemeMap}.
 * Every other component name is delegated to the inner theme untouched.
 *
 * @example ThemeManager::set(new FormsTheme(new DefaultTheme()))
 */
final class FormsTheme implements ThemeInterface
{
    public function __construct(
        private readonly ThemeInterface $inner,
    ) {
    }

    /**
     * The decorated (non-forms) theme this instance wraps.
     */
    public function inner(): ThemeInterface
    {
        return $this->inner;
    }

    public function classes(string $component, array $state = []): array
    {
        if (str_starts_with($component, 'forms.')) {
            return FormsThemeMap::classes($component, $state, $this->inner);
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
