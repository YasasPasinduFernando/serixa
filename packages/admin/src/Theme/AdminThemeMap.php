<?php

declare(strict_types=1);

namespace Serixa\Admin\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\ShadowToken;
use Serixa\Token\TypographyToken;

/**
 * Tailwind class lists for every `admin.*` component name.
 *
 * Not part of the public API surface — consumed exclusively by
 * {@see AdminTheme::classes()}. Colors, radii and shadows are borrowed from
 * the decorated inner theme where sensible, so serixa/admin follows the
 * host application's palette instead of hard-coding one.
 *
 * @internal
 */
final class AdminThemeMap
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
            'admin.shell' => self::shell($inner),

            'admin.sidebar' => self::sidebar($inner),
            'admin.sidebar.list' => self::sidebarList(),
            'admin.sidebar.group' => self::sidebarGroup(),
            'admin.sidebar.group.label' => self::sidebarGroupLabel($inner),
            'admin.sidebar.group.list' => self::sidebarGroupList(),
            'admin.sidebar.group.summary' => self::sidebarGroupSummary($inner),
            'admin.sidebar.item' => self::sidebarItem($state, $inner),
            'admin.sidebar.item.icon' => self::sidebarItemIcon($state),
            'admin.sidebar.item.label' => self::sidebarItemLabel(),
            'admin.sidebar.item.badge' => self::sidebarItemBadge($inner),
            'admin.sidebar.item.children' => self::sidebarItemChildren(),

            'admin.topBar' => self::topBar($inner),
            'admin.topBar.brand' => self::topBarBrand($inner),
            'admin.topBar.search' => self::topBarSearch(),
            'admin.topBar.actions' => self::topBarActions(),

            'admin.footer' => self::footer($inner),

            'admin.mainContent' => self::mainContent(),

            'admin.pageContainer' => self::pageContainer($state),

            'admin.page' => self::page(),
            'admin.pageHeader' => self::pageHeader(),
            'admin.pageHeader.text' => self::pageHeaderText(),
            'admin.pageHeader.breadcrumb' => self::pageHeaderBreadcrumb(),
            'admin.pageHeader.actions' => self::pageHeaderActions(),
            'admin.pageTitle' => self::pageTitle($inner),
            'admin.pageDescription' => self::pageDescription($inner),
            'admin.pageActions' => self::pageActions(),

            'admin.breadcrumb' => self::breadcrumb(),
            'admin.breadcrumb.list' => self::breadcrumbList(),
            'admin.breadcrumb.item' => self::breadcrumbItem(),
            'admin.breadcrumb.link' => self::breadcrumbLink($inner),
            'admin.breadcrumb.current' => self::breadcrumbCurrent($inner),
            'admin.breadcrumb.separator' => self::breadcrumbSeparator($inner),

            'admin.toolbar' => self::toolbar(),
            'admin.toolbar.group' => self::toolbarGroup(),
            'admin.toolbar.action' => self::toolbarAction($state, $inner),
            'admin.toolbar.action.icon' => self::toolbarActionIcon(),

            'admin.metricCard' => self::metricCard($inner),
            'admin.metricCard.header' => self::metricCardHeader(),
            'admin.metricCard.icon' => self::metricCardIcon($state, $inner),
            'admin.metricCard.title' => self::metricCardTitle($inner),
            'admin.metricCard.value' => self::metricCardValue($inner),
            'admin.metricCard.trend' => self::metricCardTrend($state),
            'admin.metricGrid' => self::metricGrid(),

            'admin.infoCard' => self::infoCard($inner),
            'admin.infoCard.title' => self::infoCardTitle($inner),
            'admin.infoCard.body' => self::infoCardBody($inner),

            'admin.statistic' => self::statistic(),
            'admin.statistic.label' => self::statisticLabel($inner),
            'admin.statistic.value' => self::statisticValue($inner),

            'admin.statusBadge' => self::statusBadge($state),

            'admin.avatarGroup' => self::avatarGroup(),
            'admin.avatarGroup.item' => self::avatarGroupItem(),
            'admin.avatarGroup.overflow' => self::avatarGroupOverflow(),

            'admin.userChip' => self::userChip(),
            'admin.userChip.text' => self::userChipText(),
            'admin.userChip.name' => self::userChipName($inner),
            'admin.userChip.subtitle' => self::userChipSubtitle($inner),

            'admin.descriptionList' => self::descriptionList(),
            'admin.descriptionItem.dt' => self::descriptionItemDt($inner),
            'admin.descriptionItem.dd' => self::descriptionItemDd($inner),

            'admin.userMenu' => self::userMenu(),
            'admin.userMenu.trigger' => self::userMenuTrigger(),
            'admin.userMenu.panel' => self::userMenuPanel($inner),
            'admin.userMenu.item' => self::userMenuItem($state),
            'admin.userMenu.divider' => self::userMenuDivider(),

            'admin.emptyState' => self::emptyState($inner),

            'admin.tableToolbar' => self::tableToolbar(),
            'admin.bulkActions' => self::bulkActions($inner),
            'admin.rowActions' => self::rowActions(),
            'admin.rowActions.item' => self::rowActionsItem(),
            'admin.columnVisibility' => self::columnVisibility(),
            'admin.columnVisibility.trigger' => self::columnVisibilityTrigger($inner),
            'admin.columnVisibility.panel' => self::columnVisibilityPanel($inner),
            'admin.columnVisibility.item' => self::columnVisibilityItem(),
            'admin.tableFilters' => self::tableFilters(),
            'admin.searchToolbar' => self::searchToolbar(),

            default => throw new InvalidArgumentException(sprintf('Unknown admin theme component "%s".', $component)),
        };
    }

    /** @return list<string> */
    private static function shell(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'min-h-screen', 'w-full'],
            $inner->token(ColorToken::background()),
        );
    }

    /** @return list<string> */
    private static function sidebar(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'h-full', 'w-64', 'shrink-0', 'flex-col', 'gap-6', 'overflow-y-auto', 'p-4'],
            $inner->token(ColorToken::surface()),
            ['border-r', 'border-slate-200', 'dark:border-slate-700'],
        );
    }

    /** @return list<string> */
    private static function sidebarList(): array
    {
        return ['flex', 'flex-col', 'gap-6', 'list-none', 'p-0', 'm-0'];
    }

    /** @return list<string> */
    private static function sidebarGroup(): array
    {
        return ['flex', 'flex-col', 'gap-1'];
    }

    /** @return list<string> */
    private static function sidebarGroupLabel(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['px-3', 'mb-1', 'text-xs', 'font-semibold', 'uppercase', 'tracking-wider'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function sidebarGroupList(): array
    {
        return ['flex', 'flex-col', 'gap-1', 'list-none', 'p-0', 'm-0'];
    }

    /** @return list<string> */
    private static function sidebarGroupSummary(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'flex', 'w-full', 'cursor-pointer', 'list-none', 'items-center', 'justify-between',
                'rounded-md', 'px-3', 'py-1.5', 'text-xs', 'font-semibold', 'uppercase', 'tracking-wider',
                'marker:content-none',
            ],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function sidebarItem(array $state, ThemeInterface $inner): array
    {
        $active = (bool) ($state['active'] ?? false);
        $nested = (bool) ($state['nested'] ?? false);

        $base = [
            'flex', 'items-center', 'gap-3', 'rounded-md', 'px-3', 'text-sm', 'font-medium',
            'transition-colors',
        ];
        $size = $nested ? ['py-1.5', 'pl-9'] : ['py-2'];

        if ($active) {
            return self::$merger->merge($base, $size, [
                'bg-sky-50', 'text-sky-700', 'dark:bg-sky-500/10', 'dark:text-sky-400',
            ]);
        }

        return self::$merger->merge(
            $base,
            $size,
            $inner->token(ColorToken::textPrimary()),
            ['hover:bg-slate-100', 'dark:hover:bg-slate-800'],
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function sidebarItemIcon(array $state): array
    {
        return ['shrink-0', 'w-5', 'h-5'];
    }

    /** @return list<string> */
    private static function sidebarItemLabel(): array
    {
        return ['flex-1', 'truncate'];
    }

    /** @return list<string> */
    private static function sidebarItemBadge(ThemeInterface $inner): array
    {
        return self::$merger->merge($inner->classes('badge', ['color' => 'primary']));
    }

    /** @return list<string> */
    private static function sidebarItemChildren(): array
    {
        return ['flex', 'flex-col', 'gap-1', 'list-none', 'p-0', 'm-0', 'mt-1'];
    }

    /** @return list<string> */
    private static function topBar(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'w-full', 'items-center', 'gap-4', 'px-4', 'py-3', 'md:px-6'],
            $inner->token(ColorToken::surface()),
            ['border-b', 'border-slate-200', 'dark:border-slate-700'],
        );
    }

    /** @return list<string> */
    private static function topBarBrand(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'items-center', 'gap-2', 'text-base', 'font-semibold', 'whitespace-nowrap'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function topBarSearch(): array
    {
        return ['flex-1', 'min-w-0', 'max-w-md'];
    }

    /** @return list<string> */
    private static function topBarActions(): array
    {
        return ['flex', 'items-center', 'gap-2', 'ml-auto'];
    }

    /** @return list<string> */
    private static function footer(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['w-full', 'px-4', 'py-4', 'text-sm', 'md:px-6'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function mainContent(): array
    {
        return ['flex', 'w-full', 'flex-1', 'flex-col', 'gap-6', 'p-4', 'md:p-6'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function pageContainer(array $state): array
    {
        $maxWidths = [
            'sm' => 'max-w-screen-sm',
            'md' => 'max-w-screen-md',
            'lg' => 'max-w-screen-lg',
            'xl' => 'max-w-screen-xl',
            '2xl' => 'max-w-screen-2xl',
            'full' => 'max-w-full',
        ];
        $maxWidth = (string) ($state['maxWidth'] ?? 'xl');

        if (! isset($maxWidths[$maxWidth])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid admin.pageContainer maxWidth "%s". Allowed: %s.',
                $maxWidth,
                implode(', ', array_keys($maxWidths)),
            ));
        }

        return ['mx-auto', 'w-full', $maxWidths[$maxWidth]];
    }

    /** @return list<string> */
    private static function page(): array
    {
        return ['flex', 'w-full', 'flex-col', 'gap-6'];
    }

    /** @return list<string> */
    private static function pageHeader(): array
    {
        return ['flex', 'w-full', 'flex-col', 'gap-4', 'md:flex-row', 'md:items-start', 'md:justify-between'];
    }

    /** @return list<string> */
    private static function pageHeaderText(): array
    {
        return ['flex', 'flex-col', 'gap-1', 'min-w-0'];
    }

    /** @return list<string> */
    private static function pageHeaderBreadcrumb(): array
    {
        return ['mb-1'];
    }

    /** @return list<string> */
    private static function pageHeaderActions(): array
    {
        return ['flex', 'shrink-0', 'items-center', 'gap-2'];
    }

    /** @return list<string> */
    private static function pageTitle(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-2xl', 'font-bold', 'tracking-tight', 'm-0'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function pageDescription(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'm-0'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function pageActions(): array
    {
        return ['flex', 'flex-wrap', 'items-center', 'gap-2'];
    }

    /** @return list<string> */
    private static function breadcrumb(): array
    {
        return ['flex', 'w-full'];
    }

    /** @return list<string> */
    private static function breadcrumbList(): array
    {
        return ['flex', 'flex-wrap', 'items-center', 'gap-1.5', 'list-none', 'p-0', 'm-0', 'text-sm'];
    }

    /** @return list<string> */
    private static function breadcrumbItem(): array
    {
        return ['flex', 'items-center', 'gap-1.5'];
    }

    /** @return list<string> */
    private static function breadcrumbLink(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            $inner->token(ColorToken::textMuted()),
            ['hover:text-sky-600', 'dark:hover:text-sky-400', 'font-medium', 'no-underline'],
        );
    }

    /** @return list<string> */
    private static function breadcrumbCurrent(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            $inner->token(ColorToken::textPrimary()),
            ['font-semibold'],
        );
    }

    /** @return list<string> */
    private static function breadcrumbSeparator(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            $inner->token(ColorToken::textMuted()),
            ['select-none'],
        );
    }

    /** @return list<string> */
    private static function toolbar(): array
    {
        return ['flex', 'w-full', 'flex-wrap', 'items-center', 'justify-between', 'gap-3'];
    }

    /** @return list<string> */
    private static function toolbarGroup(): array
    {
        return ['flex', 'items-center', 'gap-2'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function toolbarAction(array $state, ThemeInterface $inner): array
    {
        $variant = (string) ($state['variant'] ?? 'default');
        $disabled = (bool) ($state['disabled'] ?? false);

        $base = [
            'inline-flex', 'items-center', 'gap-1.5', 'rounded-md', 'px-3', 'py-1.5',
            'text-sm', 'font-medium', 'transition-colors', 'border',
        ];

        $variants = [
            'default' => self::$merger->merge(
                $inner->token(ColorToken::textPrimary()),
                ['border-slate-300', 'dark:border-slate-600', 'hover:bg-slate-50', 'dark:hover:bg-slate-800'],
            ),
            'primary' => self::$merger->merge(
                ['text-white', 'border-transparent'],
                $inner->token(ColorToken::primary()),
            ),
            'danger' => self::$merger->merge(
                ['text-white', 'border-transparent'],
                $inner->token(ColorToken::danger()),
            ),
            'ghost' => ['border-transparent', 'text-slate-600', 'hover:bg-slate-100', 'dark:text-slate-300', 'dark:hover:bg-slate-800'],
        ];

        if (! isset($variants[$variant])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid admin.toolbar.action variant "%s". Allowed: %s.',
                $variant,
                implode(', ', array_keys($variants)),
            ));
        }

        return self::$merger->merge(
            $base,
            $variants[$variant],
            $disabled ? ['opacity-50', 'cursor-not-allowed', 'pointer-events-none'] : [],
        );
    }

    /** @return list<string> */
    private static function toolbarActionIcon(): array
    {
        return ['w-4', 'h-4', 'shrink-0'];
    }

    /** @return list<string> */
    private static function metricCard(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-3', 'p-5'],
            $inner->token(ColorToken::surface()),
            $inner->token(RadiusToken::large()),
            $inner->token(ShadowToken::small()),
            ['border', 'border-slate-200', 'dark:border-slate-700'],
        );
    }

    /** @return list<string> */
    private static function metricCardHeader(): array
    {
        return ['flex', 'items-start', 'justify-between', 'gap-3'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function metricCardIcon(array $state, ThemeInterface $inner): array
    {
        $colors = [
            'default' => ['bg-slate-100', 'text-slate-600', 'dark:bg-slate-800', 'dark:text-slate-300'],
            'primary' => ['bg-sky-100', 'text-sky-600', 'dark:bg-sky-500/10', 'dark:text-sky-400'],
            'success' => ['bg-emerald-100', 'text-emerald-600', 'dark:bg-emerald-500/10', 'dark:text-emerald-400'],
            'warning' => ['bg-amber-100', 'text-amber-700', 'dark:bg-amber-500/10', 'dark:text-amber-400'],
            'danger' => ['bg-rose-100', 'text-rose-600', 'dark:bg-rose-500/10', 'dark:text-rose-400'],
        ];
        $color = (string) ($state['color'] ?? 'default');

        return self::$merger->merge(
            ['inline-flex', 'items-center', 'justify-center', 'w-10', 'h-10', 'rounded-lg', 'shrink-0'],
            $colors[$color] ?? $colors['default'],
        );
    }

    /** @return list<string> */
    private static function metricCardTitle(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'font-medium'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function metricCardValue(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-3xl', 'font-bold', 'tracking-tight'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function metricCardTrend(array $state): array
    {
        $direction = (string) ($state['direction'] ?? 'neutral');

        $directions = [
            'up' => ['text-emerald-600', 'dark:text-emerald-400'],
            'down' => ['text-rose-600', 'dark:text-rose-400'],
            'neutral' => ['text-slate-500', 'dark:text-slate-400'],
        ];

        if (! isset($directions[$direction])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid admin.metricCard.trend direction "%s". Allowed: %s.',
                $direction,
                implode(', ', array_keys($directions)),
            ));
        }

        return self::$merger->merge(
            ['inline-flex', 'items-center', 'gap-1', 'text-sm', 'font-medium'],
            $directions[$direction],
        );
    }

    /** @return list<string> */
    private static function metricGrid(): array
    {
        return ['grid', 'w-full', 'grid-cols-1', 'gap-4', 'sm:grid-cols-2', 'lg:grid-cols-4'];
    }

    /** @return list<string> */
    private static function infoCard(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'flex-col', 'gap-3', 'p-5'],
            $inner->token(ColorToken::surface()),
            $inner->token(RadiusToken::large()),
            $inner->token(ShadowToken::small()),
            ['border', 'border-slate-200', 'dark:border-slate-700'],
        );
    }

    /** @return list<string> */
    private static function infoCardTitle(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            $inner->token(TypographyToken::heading()),
            $inner->token(ColorToken::textPrimary()),
            ['m-0'],
        );
    }

    /** @return list<string> */
    private static function infoCardBody(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function statistic(): array
    {
        return ['inline-flex', 'flex-col', 'gap-0.5'];
    }

    /** @return list<string> */
    private static function statisticLabel(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-xs', 'font-medium', 'uppercase', 'tracking-wide'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function statisticValue(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-lg', 'font-semibold'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function statusBadge(array $state): array
    {
        $status = (string) ($state['status'] ?? 'neutral');

        $tones = [
            'neutral' => ['bg-slate-100', 'text-slate-700', 'dark:bg-slate-700/40', 'dark:text-slate-300'],
            'info' => ['bg-sky-100', 'text-sky-700', 'dark:bg-sky-500/10', 'dark:text-sky-400'],
            'success' => ['bg-emerald-100', 'text-emerald-700', 'dark:bg-emerald-500/10', 'dark:text-emerald-400'],
            'warning' => ['bg-amber-100', 'text-amber-800', 'dark:bg-amber-500/10', 'dark:text-amber-400'],
            'danger' => ['bg-rose-100', 'text-rose-700', 'dark:bg-rose-500/10', 'dark:text-rose-400'],
        ];

        if (! isset($tones[$status])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid admin.statusBadge status "%s". Allowed: %s.',
                $status,
                implode(', ', array_keys($tones)),
            ));
        }

        return self::$merger->merge(
            ['inline-flex', 'items-center', 'gap-1', 'rounded-full', 'px-2.5', 'py-0.5', 'text-xs', 'font-semibold'],
            $tones[$status],
        );
    }

    /** @return list<string> */
    private static function avatarGroup(): array
    {
        return ['flex', 'items-center'];
    }

    /** @return list<string> */
    private static function avatarGroupItem(): array
    {
        return ['-ml-2', 'first:ml-0', 'ring-2', 'ring-white', 'dark:ring-slate-900', 'rounded-full'];
    }

    /** @return list<string> */
    private static function avatarGroupOverflow(): array
    {
        return [
            '-ml-2', 'inline-flex', 'h-10', 'w-10', 'items-center', 'justify-center', 'rounded-full',
            'ring-2', 'ring-white', 'dark:ring-slate-900', 'bg-slate-200', 'dark:bg-slate-700',
            'text-xs', 'font-semibold', 'text-slate-700', 'dark:text-slate-200',
        ];
    }

    /** @return list<string> */
    private static function userChip(): array
    {
        return ['inline-flex', 'items-center', 'gap-2', 'min-w-0'];
    }

    /** @return list<string> */
    private static function userChipText(): array
    {
        return ['flex', 'flex-col', 'min-w-0', 'leading-tight'];
    }

    /** @return list<string> */
    private static function userChipName(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'font-medium', 'truncate'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function userChipSubtitle(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-xs', 'truncate'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function descriptionList(): array
    {
        return ['grid', 'w-full', 'grid-cols-1', 'gap-x-6', 'gap-y-4', 'sm:grid-cols-2'];
    }

    /** @return list<string> */
    private static function descriptionItemDt(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-xs', 'font-medium', 'uppercase', 'tracking-wide', 'm-0'],
            $inner->token(ColorToken::textMuted()),
        );
    }

    /** @return list<string> */
    private static function descriptionItemDd(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['text-sm', 'mt-1', 'mb-0'],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function userMenu(): array
    {
        return ['relative', 'inline-block'];
    }

    /** @return list<string> */
    private static function userMenuTrigger(): array
    {
        return [
            'flex', 'cursor-pointer', 'list-none', 'items-center', 'gap-2', 'rounded-md', 'p-1',
            'hover:bg-slate-100', 'dark:hover:bg-slate-800', 'marker:content-none',
        ];
    }

    /** @return list<string> */
    private static function userMenuPanel(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['absolute', 'right-0', 'z-20', 'mt-2', 'min-w-[12rem]', 'py-1'],
            $inner->token(ColorToken::surface()),
            $inner->token(RadiusToken::medium()),
            $inner->token(ShadowToken::large()),
            ['border', 'border-slate-200', 'dark:border-slate-700'],
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private static function userMenuItem(array $state): array
    {
        $danger = (bool) ($state['danger'] ?? false);

        $base = ['block', 'w-full', 'px-3', 'py-2', 'text-left', 'text-sm', 'no-underline'];

        return self::$merger->merge(
            $base,
            $danger
                ? ['text-rose-600', 'hover:bg-rose-50', 'dark:text-rose-400', 'dark:hover:bg-rose-500/10']
                : ['text-slate-700', 'hover:bg-slate-50', 'dark:text-slate-200', 'dark:hover:bg-slate-800'],
        );
    }

    /** @return list<string> */
    private static function userMenuDivider(): array
    {
        return ['my-1', 'border-t', 'border-slate-200', 'dark:border-slate-700'];
    }

    /** @return list<string> */
    private static function emptyState(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            $inner->token(RadiusToken::large()),
            ['border', 'border-dashed', 'border-slate-300', 'dark:border-slate-700', 'w-full'],
        );
    }

    /** @return list<string> */
    private static function tableToolbar(): array
    {
        return ['flex', 'w-full', 'flex-wrap', 'items-center', 'justify-between', 'gap-3'];
    }

    /** @return list<string> */
    private static function bulkActions(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['flex', 'items-center', 'gap-2', 'rounded-md', 'px-3', 'py-1.5', 'text-sm'],
            $inner->token(ColorToken::textPrimary()),
            ['bg-sky-50', 'dark:bg-sky-500/10'],
        );
    }

    /** @return list<string> */
    private static function rowActions(): array
    {
        return ['flex', 'items-center', 'justify-end', 'gap-2'];
    }

    /** @return list<string> */
    private static function rowActionsItem(): array
    {
        return self::$merger->merge(
            ['text-sm', 'font-medium', 'no-underline'],
            ['text-sky-600', 'hover:text-sky-700', 'dark:text-sky-400'],
        );
    }

    /** @return list<string> */
    private static function columnVisibility(): array
    {
        return ['relative', 'inline-block'];
    }

    /** @return list<string> */
    private static function columnVisibilityTrigger(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            [
                'inline-flex', 'cursor-pointer', 'list-none', 'items-center', 'gap-1.5', 'rounded-md',
                'border', 'border-slate-300', 'dark:border-slate-600', 'px-3', 'py-1.5', 'text-sm',
                'font-medium', 'marker:content-none', 'hover:bg-slate-50', 'dark:hover:bg-slate-800',
            ],
            $inner->token(ColorToken::textPrimary()),
        );
    }

    /** @return list<string> */
    private static function columnVisibilityPanel(ThemeInterface $inner): array
    {
        return self::$merger->merge(
            ['absolute', 'right-0', 'z-20', 'mt-2', 'min-w-[12rem]', 'py-2'],
            $inner->token(ColorToken::surface()),
            $inner->token(RadiusToken::medium()),
            $inner->token(ShadowToken::large()),
            ['border', 'border-slate-200', 'dark:border-slate-700'],
        );
    }

    /** @return list<string> */
    private static function columnVisibilityItem(): array
    {
        return ['flex', 'items-center', 'gap-2', 'px-3', 'py-1.5', 'text-sm', 'text-slate-700', 'dark:text-slate-200'];
    }

    /** @return list<string> */
    private static function tableFilters(): array
    {
        return ['flex', 'w-full', 'flex-wrap', 'items-center', 'gap-3'];
    }

    /** @return list<string> */
    private static function searchToolbar(): array
    {
        return ['flex', 'w-full', 'flex-wrap', 'items-center', 'gap-3'];
    }
}
