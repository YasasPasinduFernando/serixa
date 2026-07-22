<?php

declare(strict_types=1);

namespace Serixa\Charts\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\TypographyToken;

/**
 * Tailwind class lists for every `charts.*` component name.
 *
 * @internal
 */
final class ChartsThemeMap
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
            'charts.root' => self::root($inner),
            'charts.figure' => self::figure($inner),
            'charts.svg' => self::svg(),
            'charts.table' => self::table($state, $inner),
            'charts.legend' => self::legend($inner),
            'charts.axis' => self::axis($inner),
            'charts.grid' => self::grid(),
            'charts.tooltip' => self::tooltip($inner),
            'charts.title' => self::title($inner),
            'charts.caption' => self::caption($inner),
            'charts.series' => self::series($state),
            'charts.slice' => self::slice($state),
            'charts.ring' => self::ring($state),
            'charts.gauge' => self::gauge($state),
            'charts.heatmap' => self::heatmap($state),
            'charts.timeline' => self::timeline($inner),
            'charts.sparkline' => self::sparkline(),
            default => throw new InvalidArgumentException(sprintf('Unknown charts theme component "%s".', $component)),
        };
    }

    /** @return list<string> */
    private static function root(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'flex', 'flex-col', 'gap-2'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function figure(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['m-0', 'w-full', 'flex', 'flex-col', 'gap-2'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function svg(): array
    {
        return ['w-full', 'h-auto', 'block', 'overflow-visible'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function table(array $state, ThemeInterface $inner): array
    {
        $srOnly = ($state['srOnly'] ?? true) === true;

        if ($srOnly) {
            return [
                'sr-only',
                'absolute',
                'w-px',
                'h-px',
                'p-0',
                '-m-px',
                'overflow-hidden',
                'whitespace-nowrap',
                'border-0',
            ];
        }

        return self::$merger->merge(
            [
                'w-full',
                'text-sm',
                'border-collapse',
                'border',
                'border-slate-200',
                'dark:border-slate-700',
            ],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function legend(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'flex',
                'flex-wrap',
                'items-center',
                'gap-x-4',
                'gap-y-1',
                'text-sm',
                'm-0',
                'p-0',
                'list-none',
            ],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function axis(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['fill-current', 'text-xs'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function grid(): array
    {
        return ['stroke-slate-200', 'dark:stroke-slate-700', 'stroke-1'];
    }

    /** @return list<string> */
    private static function tooltip(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'text-xs',
                'px-2',
                'py-1',
                'bg-slate-900',
                'dark:bg-slate-100',
                'text-slate-50',
                'dark:text-slate-900',
            ],
            $inner->token(RadiusToken::medium()),
        );
    }

    /** @return list<string> */
    private static function title(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-base', 'font-semibold', 'm-0', 'tracking-tight'],
            $inner->token(TypographyToken::heading()),
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function caption(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'm-0'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function series(array $state): array
    {
        $index = (int) ($state['index'] ?? 0);

        return [
            'charts-series',
            'charts-series-' . $index,
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function slice(array $state): array
    {
        $index = (int) ($state['index'] ?? 0);

        return [
            'charts-slice',
            'charts-slice-' . $index,
            'charts-point',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function ring(array $state): array
    {
        return [
            'charts-ring',
            (($state['track'] ?? false) === true) ? 'charts-ring-track' : 'charts-ring-value',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function gauge(array $state): array
    {
        return [
            'charts-gauge',
            (($state['zone'] ?? false) === true) ? 'charts-gauge-zone' : 'charts-gauge-needle',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function heatmap(array $state): array
    {
        $intensity = (int) ($state['intensity'] ?? 0);

        return [
            'charts-heatmap-cell',
            'charts-heatmap-i' . max(0, min(9, $intensity)),
        ];
    }

    /** @return list<string> */
    private static function timeline(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['charts-timeline', 'stroke-slate-300', 'dark:stroke-slate-600'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function sparkline(): array
    {
        return ['charts-sparkline', 'w-full', 'h-auto', 'block'];
    }
}
