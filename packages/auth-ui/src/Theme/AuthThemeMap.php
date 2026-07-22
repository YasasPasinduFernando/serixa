<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\ShadowToken;
use Serixa\Token\TypographyToken;

/**
 * Tailwind class lists for every `auth.*` component name.
 *
 * @internal
 */
final class AuthThemeMap
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
            'auth.layout' => self::layout($state, $inner),
            'auth.layout.panel' => self::layoutPanel(),
            'auth.card' => self::card($inner),
            'auth.header' => self::header($inner),
            'auth.footer' => self::footer($inner),
            'auth.aside' => self::aside(),
            'auth.illustration' => self::illustration(),
            'auth.brand' => self::brand($inner),
            'auth.divider' => self::divider($inner),
            'auth.social' => self::social(),
            'auth.social.button' => self::socialButton($inner),
            'auth.strength' => self::strength($inner),
            'auth.strength.bar' => self::strengthBar($inner),
            'auth.strength.weak' => self::strengthLevel('weak'),
            'auth.strength.fair' => self::strengthLevel('fair'),
            'auth.strength.good' => self::strengthLevel('good'),
            'auth.strength.strong' => self::strengthLevel('strong'),
            'auth.requirements' => self::requirements(),
            'auth.requirements.item' => self::requirementsItem($state, $inner),
            'auth.title' => self::title($inner),
            'auth.subtitle' => self::subtitle($inner),
            'auth.link' => self::link($inner),
            'auth.alert' => self::alert($inner),
            default => throw new InvalidArgumentException(sprintf('Unknown auth theme component "%s".', $component)),
        };
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function layout(array $state, ThemeInterface $inner): array
    {
        $split = ($state['split'] ?? false) === true;

        $base = self::$merger->merge(
            [
                'min-h-screen',
                'w-full',
                'bg-slate-50',
                'dark:bg-slate-950',
                'text-slate-900',
                'dark:text-slate-100',
            ],
            $inner->token(ColorToken::textPrimary()),
        );

        if ($split) {
            return self::$merger->merge(
                $base,
                ['grid', 'grid-cols-1', 'lg:grid-cols-2'],
            );
        }

        return self::$merger->merge(
            $base,
            [
                'flex',
                'flex-col',
                'items-center',
                'justify-center',
                'px-4',
                'py-12',
            ],
        );
    }

    /** @return list<string> */
    private static function layoutPanel(): array
    {
        return [
            'flex',
            'flex-col',
            'items-center',
            'justify-center',
            'px-4',
            'py-12',
            'w-full',
        ];
    }

    /** @return list<string> */
    private static function card(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'w-full',
                'max-w-md',
                'flex',
                'flex-col',
                'gap-6',
                'bg-white',
                'dark:bg-slate-900',
                'border',
                'border-slate-200',
                'dark:border-slate-800',
                'px-6',
                'py-8',
                'sm:px-8',
            ],
            $inner->token(RadiusToken::large()),
            $inner->token(ShadowToken::medium()),
        );
    }

    /** @return list<string> */
    private static function header(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'items-center', 'gap-2', 'text-center', 'w-full'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function footer(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'flex',
                'flex-wrap',
                'items-center',
                'justify-center',
                'gap-x-4',
                'gap-y-2',
                'text-sm',
                'w-full',
                'pt-2',
            ],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function aside(): array
    {
        return [
            'hidden',
            'lg:flex',
            'flex-col',
            'justify-center',
            'gap-6',
            'px-12',
            'py-16',
            'bg-slate-900',
            'text-slate-50',
            'dark:bg-slate-950',
        ];
    }

    /** @return list<string> */
    private static function illustration(): array
    {
        return [
            'hidden',
            'lg:flex',
            'items-center',
            'justify-center',
            'overflow-hidden',
            'bg-teal-700',
            'dark:bg-teal-900',
            'text-teal-50',
        ];
    }

    /** @return list<string> */
    private static function brand(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'inline-flex',
                'items-center',
                'gap-2',
                'text-lg',
                'font-semibold',
                'tracking-tight',
                'text-slate-900',
                'dark:text-slate-50',
                'no-underline',
            ],
            $inner->token(TypographyToken::heading()),
        );
    }

    /** @return list<string> */
    private static function divider(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'relative',
                'flex',
                'items-center',
                'justify-center',
                'gap-3',
                'w-full',
                'text-xs',
                'font-medium',
                'uppercase',
                'tracking-wide',
                'text-slate-500',
                'dark:text-slate-400',
                'before:content-[\'\']',
                'before:flex-1',
                'before:h-px',
                'before:bg-slate-200',
                'dark:before:bg-slate-700',
                'after:content-[\'\']',
                'after:flex-1',
                'after:h-px',
                'after:bg-slate-200',
                'dark:after:bg-slate-700',
            ],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function social(): array
    {
        return ['flex', 'flex-col', 'gap-2', 'w-full'];
    }

    /** @return list<string> */
    private static function socialButton(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'inline-flex',
                'w-full',
                'items-center',
                'justify-center',
                'gap-2',
                'px-4',
                'py-2.5',
                'text-sm',
                'font-medium',
                'border',
                'border-slate-300',
                'dark:border-slate-600',
                'bg-white',
                'dark:bg-slate-900',
                'text-slate-800',
                'dark:text-slate-100',
                'no-underline',
                'hover:bg-slate-50',
                'dark:hover:bg-slate-800',
                'focus-visible:outline',
                'focus-visible:outline-2',
                'focus-visible:outline-offset-2',
                'focus-visible:outline-teal-600',
            ],
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function strength(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-1.5', 'w-full'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function strengthBar(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'h-1.5',
                'w-full',
                'overflow-hidden',
                'bg-slate-200',
                'dark:bg-slate-700',
            ],
            $inner->token(RadiusToken::full()),
        );
    }

    /** @return list<string> */
    private static function strengthLevel(string $level): array
    {
        $fill = match ($level) {
            'weak' => ['w-1/4', 'bg-rose-500', 'dark:bg-rose-400'],
            'fair' => ['w-2/4', 'bg-amber-500', 'dark:bg-amber-400'],
            'good' => ['w-3/4', 'bg-teal-500', 'dark:bg-teal-400'],
            'strong' => ['w-full', 'bg-emerald-500', 'dark:bg-emerald-400'],
            default => ['w-0', 'bg-transparent'],
        };

        return self::$merger->merge(
            ['h-full', 'transition-all', 'duration-200'],
            $fill,
        );
    }

    /** @return list<string> */
    private static function requirements(): array
    {
        return ['flex', 'flex-col', 'gap-1.5', 'w-full', 'm-0', 'p-0', 'list-none'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function requirementsItem(array $state, ThemeInterface $inner): array
    {
        $met = ($state['met'] ?? false) === true;

        return self::$merger->merge(
            [
                'flex',
                'items-center',
                'gap-2',
                'text-sm',
                $met ? 'text-emerald-700 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400',
            ],
            $met ? [] : $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function title(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-2xl', 'font-semibold', 'tracking-tight', 'm-0'],
            $inner->token(TypographyToken::heading()),
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function subtitle(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'm-0'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function link(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'text-sm',
                'font-medium',
                'text-teal-700',
                'dark:text-teal-400',
                'underline-offset-2',
                'hover:underline',
                'focus-visible:outline',
                'focus-visible:outline-2',
                'focus-visible:outline-offset-2',
                'focus-visible:outline-teal-600',
            ],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function alert(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'rounded-md',
                'border',
                'border-teal-200',
                'dark:border-teal-800',
                'bg-teal-50',
                'dark:bg-teal-950',
                'px-4',
                'py-3',
                'text-sm',
                'text-teal-900',
                'dark:text-teal-100',
            ],
            $inner->token(RadiusToken::medium()),
        );
    }
}
