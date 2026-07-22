<?php

declare(strict_types=1);

namespace Serixa\Forms\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\TypographyToken;

/**
 * Tailwind class lists for every `forms.*` component name.
 *
 * @internal
 */
final class FormsThemeMap
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
            'forms.input' => self::input($inner),
            'forms.error' => self::error(),
            'forms.success' => self::success(),
            'forms.warning' => self::warning(),
            'forms.label' => self::label($inner),
            'forms.help' => self::help($inner),
            'forms.section' => self::section($inner),
            'forms.section.title' => self::sectionTitle($inner),
            'forms.section.description' => self::sectionDescription($inner),
            'forms.summary' => self::summary(),
            'forms.fieldset' => self::fieldset($inner),
            'forms.legend' => self::legend($inner),
            'forms.grid' => self::grid(),
            'forms.inline' => self::inline(),
            'forms.group' => self::group(),
            default => throw new InvalidArgumentException(sprintf('Unknown forms theme component "%s".', $component)),
        };
    }

    /** @return list<string> */
    private static function input(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-1', 'w-full'],
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function error(): array
    {
        return ['text-xs', 'font-medium', 'text-rose-600', 'dark:text-rose-400', 'm-0'];
    }

    /** @return list<string> */
    private static function success(): array
    {
        return [
            'rounded-md',
            'border',
            'border-emerald-200',
            'dark:border-emerald-800',
            'bg-emerald-50',
            'dark:bg-emerald-950',
            'px-3',
            'py-2',
            'text-sm',
            'font-medium',
            'text-emerald-800',
            'dark:text-emerald-200',
        ];
    }

    /** @return list<string> */
    private static function warning(): array
    {
        return [
            'rounded-md',
            'border',
            'border-amber-200',
            'dark:border-amber-800',
            'bg-amber-50',
            'dark:bg-amber-950',
            'px-3',
            'py-2',
            'text-sm',
            'font-medium',
            'text-amber-800',
            'dark:text-amber-200',
        ];
    }

    /** @return list<string> */
    private static function label(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['block', 'text-sm', 'font-medium', 'mb-1'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function help(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-xs', 'm-0'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function section(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-4', 'w-full', 'py-4'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function sectionTitle(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-base', 'font-semibold', 'm-0'],
            $inner->token(TypographyToken::heading()),
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function sectionDescription(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'm-0'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function summary(): array
    {
        return [
            'rounded-md',
            'border',
            'border-rose-200',
            'dark:border-rose-800',
            'bg-rose-50',
            'dark:bg-rose-950',
            'px-4',
            'py-3',
            'text-sm',
            'text-rose-800',
            'dark:text-rose-200',
        ];
    }

    /** @return list<string> */
    private static function fieldset(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-3', 'w-full', 'border', 'border-slate-200', 'dark:border-slate-700', 'p-4'],
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function legend(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['px-1', 'text-sm', 'font-semibold'],
            $inner->token(TypographyToken::heading()),
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function grid(): array
    {
        return ['grid', 'w-full', 'grid-cols-1', 'gap-4', 'sm:grid-cols-2'];
    }

    /** @return list<string> */
    private static function inline(): array
    {
        return ['flex', 'flex-wrap', 'items-end', 'gap-3', 'w-full'];
    }

    /** @return list<string> */
    private static function group(): array
    {
        return ['flex', 'flex-col', 'gap-1', 'w-full'];
    }
}
