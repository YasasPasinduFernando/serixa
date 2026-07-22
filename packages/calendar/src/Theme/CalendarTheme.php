<?php

declare(strict_types=1);

namespace Serixa\Calendar\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Token\DesignToken;

/**
 * Decorates a host {@see ThemeInterface} with the `calendar.*` vocabulary.
 *
 * @example ThemeManager::set(new CalendarTheme(new DefaultTheme()))
 */
final class CalendarTheme implements ThemeInterface
{
    public function __construct(
        private readonly ThemeInterface $inner,
    ) {
    }

    public function inner(): ThemeInterface
    {
        return $this->inner;
    }

    public function classes(string $component, array $state = []): array
    {
        if (str_starts_with($component, 'calendar.')) {
            return CalendarThemeMap::classes($component, $state, $this->inner);
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
