<?php

declare(strict_types=1);

namespace Serixa\Calendar\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\TypographyToken;

/**
 * Tailwind class lists for every `calendar.*` component name.
 *
 * @internal
 */
final class CalendarThemeMap
{
    private static ClassMerger $merger;

    private function __construct()
    {
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    public static function classes(string $component, array $state, ThemeInterface $inner): array
    {
        self::$merger ??= new ClassMerger();

        return match ($component) {
            'calendar.root' => self::root($inner),
            'calendar.toolbar' => self::toolbar($inner),
            'calendar.title' => self::title($inner),
            'calendar.grid' => self::grid($inner),
            'calendar.weekday' => self::weekday($inner),
            'calendar.day' => self::day($state, $inner),
            'calendar.dayNumber' => self::dayNumber($state, $inner),
            'calendar.event' => self::event($state, $inner),
            'calendar.badge' => self::badge($inner),
            'calendar.agenda' => self::agenda($inner),
            'calendar.agendaItem' => self::agendaItem($inner),
            'calendar.card' => self::card($inner),
            'calendar.popover' => self::popover($inner),
            'calendar.legend' => self::legend($inner),
            'calendar.picker' => self::picker($inner),
            'calendar.timeGrid' => self::timeGrid($inner),
            'calendar.resource' => self::resource($inner),
            'calendar.timeline' => self::timeline($inner),
            'calendar.live' => ['sr-only', 'absolute', 'w-px', 'h-px', 'overflow-hidden'],
            default => throw new InvalidArgumentException(sprintf('Unknown calendar theme component "%s".', $component)),
        };
    }

    /** @return list<string> */
    private static function root(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'flex', 'flex-col', 'gap-3'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function toolbar(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-wrap', 'items-center', 'justify-between', 'gap-2'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function title(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            $inner->token(TypographyToken::heading()),
            ['font-semibold'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function grid(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'border-collapse', 'table-fixed'],
            $inner->token(ColorToken::surface()),
        );
    }

    /** @return list<string> */
    private static function weekday(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-center', 'text-xs', 'font-medium', 'uppercase', 'tracking-wide', 'py-2'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function day(array $state, ThemeInterface $inner): array
    {
        $base = ['align-top', 'border', 'border-slate-200', 'p-1', 'h-24', 'relative', 'align-top'];

        if (($state['outside'] ?? false) === true) {
            $base[] = 'opacity-40';
        }

        if (($state['today'] ?? false) === true) {
            $base = self::$merger->merge($base, ['ring-2', 'ring-sky-500', 'ring-inset']);
        }

        if (($state['selected'] ?? false) === true) {
            $base = self::$merger->merge($base, ['bg-sky-50']);
        }

        return self::$merger->merge($base, $inner->token(ColorToken::surface()));
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function dayNumber(array $state, ThemeInterface $inner): array
    {
        $base = ['inline-flex', 'items-center', 'justify-center', 'w-7', 'h-7', 'text-sm', 'rounded-full'];

        if (($state['today'] ?? false) === true) {
            $base = self::$merger->merge($base, ['bg-sky-600', 'text-white', 'font-semibold']);
        } else {
            $base = self::$merger->merge($base, $inner->token(ColorToken::textPrimary()));
        }

        return $base;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function event(array $state, ThemeInterface $inner): array
    {
        $base = self::$merger->merge(
            ['block', 'text-xs', 'truncate', 'px-1', 'py-0.5', 'mb-0.5', 'rounded'],
            $inner->token(RadiusToken::small()),
            ['bg-sky-100', 'text-sky-900'],
        );

        if (($state['compact'] ?? false) === true) {
            $base[] = 'leading-tight';
        }

        return $base;
    }

    /** @return list<string> */
    private static function badge(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['inline-flex', 'items-center', 'gap-1', 'text-xs', 'px-1.5', 'py-0.5', 'rounded-full', 'bg-sky-100', 'text-sky-800'],
            $inner->token(RadiusToken::full()),
        );
    }

    /** @return list<string> */
    private static function agenda(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-2', 'list-none', 'm-0', 'p-0'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function agendaItem(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'gap-3', 'items-start', 'border-b', 'border-slate-200', 'py-2'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function card(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['border', 'border-slate-200', 'p-3', 'flex', 'flex-col', 'gap-1'],
            $inner->token(ColorToken::surface()),
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function popover(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['border', 'border-slate-200', 'p-3', 'shadow-sm', 'max-w-xs'],
            $inner->token(ColorToken::surface()),
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function legend(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-wrap', 'gap-3', 'text-sm'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function picker(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['inline-flex', 'flex-col', 'gap-2'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function timeGrid(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'border-collapse'],
            $inner->token(ColorToken::surface()),
        );
    }

    /** @return list<string> */
    private static function resource(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'border-collapse', 'table-fixed'],
            $inner->token(ColorToken::surface()),
        );
    }

    /** @return list<string> */
    private static function timeline(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'relative', 'min-h-[120px]'],
            $inner->token(ColorToken::surface()),
        );
    }
}
