<?php

declare(strict_types=1);

namespace Serixa\DevTools\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\TypographyToken;

/**
 * Tailwind class lists for every `devtools.*` component name.
 *
 * @internal
 */
final class DevToolsThemeMap
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
            'devtools.root' => self::root($inner),
            'devtools.toolbar' => self::toolbar($inner),
            'devtools.panel' => self::panel($inner),
            'devtools.tab' => self::tab($state, $inner),
            'devtools.badge' => self::badge($inner),
            'devtools.overlay' => self::overlay($inner),
            'devtools.highlight' => self::highlight(),
            'devtools.console' => self::console($inner),
            default => throw new InvalidArgumentException(sprintf('Unknown devtools theme component "%s".', $component)),
        };
    }

    /** @return list<string> */
    private static function root(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['font-sans', 'text-sm', 'antialiased'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function toolbar(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'fixed',
                'bottom-0',
                'inset-x-0',
                'z-[9999]',
                'flex',
                'items-center',
                'gap-2',
                'px-3',
                'py-2',
                'border-t',
                'border-slate-700',
                'bg-slate-900',
                'text-slate-100',
                'shadow-lg',
            ],
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function panel(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'fixed',
                'bottom-12',
                'inset-x-0',
                'z-[9998]',
                'max-h-[40vh]',
                'overflow-auto',
                'border-t',
                'border-slate-700',
                'bg-slate-950',
                'text-slate-100',
                'p-4',
            ],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function tab(array $state, ThemeInterface $inner): array
    {
        $active = ($state['active'] ?? false) === true;

        $base = [
            'px-3',
            'py-1',
            'text-xs',
            'font-medium',
            'cursor-pointer',
            'border',
            'border-transparent',
        ];

        if ($active) {
            return self::$merger->merge(
                $base,
                ['bg-slate-700', 'text-white'],
                $inner->token(RadiusToken::medium()),
            );
        }

        return self::$merger->merge(
            $base,
            ['text-slate-300', 'hover:text-white', 'hover:bg-slate-800'],
            $inner->token(RadiusToken::medium()),
            $inner->token(TypographyToken::label()),
        );
    }

    /** @return list<string> */
    private static function badge(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'inline-flex',
                'items-center',
                'px-1.5',
                'py-0.5',
                'text-xs',
                'font-medium',
                'bg-sky-600',
                'text-white',
            ],
            $inner->token(RadiusToken::medium()),
            $inner->token(TypographyToken::label()),
        );
    }

    /** @return list<string> */
    private static function overlay(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['serixa-devtools', 'pointer-events-auto'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function highlight(): array
    {
        return [
            'outline',
            'outline-2',
            'outline-offset-2',
            'outline-sky-400',
        ];
    }

    /** @return list<string> */
    private static function console(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'font-mono',
                'text-xs',
                'bg-slate-900',
                'text-emerald-400',
                'p-3',
                'rounded',
                'overflow-x-auto',
                'whitespace-pre-wrap',
            ],
            $inner->token(RadiusToken::medium()),
            $inner->token(TypographyToken::label()),
        );
    }
}
