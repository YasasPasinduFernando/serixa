<?php

declare(strict_types=1);

namespace Serixa\Theme;

use Serixa\Contracts\ThemeInterface;
use Serixa\Exception\InvalidArgumentException;
use Serixa\Support\ClassMerger;
use Serixa\Token\ColorToken;
use Serixa\Token\DesignToken;
use Serixa\Token\RadiusToken;
use Serixa\Token\ShadowToken;
use Serixa\Token\SpacingToken;
use Serixa\Token\TypographyToken;

/**
 * Default Tailwind-backed theme.
 *
 * Widget class maps compose design tokens; tokens alone are swappable per theme.
 */
final class DefaultTheme implements ThemeInterface
{
    /** @var list<int|float|string> */
    private const SPACING_SCALE = [0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14, 16, 20, 24, 28, 32, 36, 40, 44, 48, 52, 56, 60, 64, 72, 80, 96];

    public function __construct(
        private readonly ClassMerger $classMerger = new ClassMerger(),
    ) {
    }

    public function classes(string $component, array $state = []): array
    {
        return match ($component) {
            'button' => $this->button($state),
            'text' => $this->text($state),
            'card' => $this->card($state),
            'card.title' => $this->cardTitle($state),
            'container' => $this->container($state),
            'row' => $this->row($state),
            'column' => $this->column($state),
            'center' => $this->center($state),
            'padding' => $this->padding($state),
            'spacer' => $this->spacer($state),
            'expanded' => $this->expanded($state),
            'sizedBox' => $this->sizedBox($state),
            'stack' => $this->stack($state),
            'divider' => $this->divider($state),
            'scaffold' => $this->scaffold($state),
            'scaffold.appBar' => $this->scaffoldAppBar($state),
            'scaffold.sidebar' => $this->scaffoldSidebar($state),
            'scaffold.main' => $this->scaffoldMain($state),
            'scaffold.body' => $this->scaffoldBody($state),
            'scaffold.footer' => $this->scaffoldFooter($state),
            'appBar' => $this->appBar($state),
            'appBar.title' => $this->appBarTitle($state),
            'appBar.actions' => $this->appBarActions($state),
            'sidebar' => $this->sidebar($state),
            'mainContent' => $this->mainContent($state),
            'footer' => $this->footerBar($state),
            'form' => $this->form($state),
            'label' => $this->label($state),
            'input' => $this->input($state),
            'textarea' => $this->textarea($state),
            'checkbox' => $this->checkbox($state),
            'checkbox.wrap' => $this->choiceWrap($state),
            'checkbox.label' => $this->choiceLabel($state),
            'radio' => $this->radio($state),
            'radio.wrap' => $this->choiceWrap($state),
            'radio.label' => $this->choiceLabel($state),
            'switch' => $this->switchControl($state),
            'switch.wrap' => $this->choiceWrap($state),
            'switch.label' => $this->choiceLabel($state),
            'select' => $this->select($state),
            'fieldGroup' => $this->fieldGroup($state),
            'helperText' => $this->helperText($state),
            'errorText' => $this->errorText($state),
            'link' => $this->link($state),
            'table' => $this->table($state),
            'tableHead' => $this->tableHead($state),
            'tableBody' => $this->tableBody($state),
            'tableRow' => $this->tableRow($state),
            'tableCell' => $this->tableCell($state),
            'tableHeaderCell' => $this->tableHeaderCell($state),
            'dataTable' => $this->dataTable($state),
            'dataTable.scroll' => $this->dataTableScroll($state),
            'dataTable.caption' => $this->dataTableCaption($state),
            'searchField' => $this->searchField($state),
            'searchField.input' => $this->searchFieldInput($state),
            'searchField.icon' => $this->searchFieldIcon($state),
            'pagination' => $this->pagination($state),
            'pagination.list' => $this->paginationList($state),
            'pagination.item' => $this->paginationItem($state),
            'pagination.link' => $this->paginationLink($state),
            'pagination.ellipsis' => $this->paginationEllipsis($state),
            'avatar' => $this->avatar($state),
            'avatar.image' => $this->avatarImage($state),
            'avatar.initials' => $this->avatarInitials($state),
            'image' => $this->image($state),
            'logo' => $this->logo($state),
            'logo.image' => $this->logoImage($state),
            'logo.text' => $this->logoText($state),
            'statCard' => $this->statCard($state),
            'statCard.icon' => $this->statCardIcon($state),
            'statCard.title' => $this->statCardTitle($state),
            'statCard.value' => $this->statCardValue($state),
            'statCard.subtitle' => $this->statCardSubtitle($state),
            'statCard.trend' => $this->statCardTrend($state),
            'metric' => $this->metric($state),
            'metric.label' => $this->metricLabel($state),
            'metric.value' => $this->metricValue($state),
            'emptyState' => $this->emptyState($state),
            'emptyState.icon' => $this->emptyStateIcon($state),
            'emptyState.title' => $this->emptyStateTitle($state),
            'emptyState.description' => $this->emptyStateDescription($state),
            'emptyState.action' => $this->emptyStateAction($state),
            'activityList' => $this->activityList($state),
            'activityItem' => $this->activityItem($state),
            'activityItem.icon' => $this->activityItemIcon($state),
            'activityItem.body' => $this->activityItemBody($state),
            'activityItem.title' => $this->activityItemTitle($state),
            'activityItem.description' => $this->activityItemDescription($state),
            'activityItem.timestamp' => $this->activityItemTimestamp($state),
            'pageHeader' => $this->pageHeader($state),
            'pageHeader.text' => $this->pageHeaderText($state),
            'pageHeader.title' => $this->pageHeaderTitle($state),
            'pageHeader.subtitle' => $this->pageHeaderSubtitle($state),
            'pageHeader.actions' => $this->pageHeaderActions($state),
            'toolbar' => $this->toolbar($state),
            'toolbar.leading' => $this->toolbarLeading($state),
            'toolbar.center' => $this->toolbarCenter($state),
            'toolbar.trailing' => $this->toolbarTrailing($state),
            default => throw new InvalidArgumentException(sprintf('Unknown theme component "%s".', $component)),
        };
    }

    public function token(DesignToken|string $token): array
    {
        $name = $token instanceof DesignToken ? $token->name() : $token;
        $map = $this->tokenMap();

        if (! isset($map[$name])) {
            throw new InvalidArgumentException(sprintf('Unknown design token "%s".', $name));
        }

        return $map[$name];
    }

    public function buttonPrimary(): array
    {
        return $this->classes('button', ['variant' => 'primary']);
    }

    public function buttonSecondary(): array
    {
        return $this->classes('button', ['variant' => 'secondary']);
    }

    public function buttonDanger(): array
    {
        return $this->classes('button', ['variant' => 'danger']);
    }

    public function buttonGhost(): array
    {
        return $this->classes('button', ['variant' => 'ghost']);
    }

    /**
     * @return array<string, list<string>>
     */
    private function tokenMap(): array
    {
        return [
            ColorToken::primary()->name() => ['bg-sky-600', 'hover:bg-sky-700'],
            ColorToken::secondary()->name() => ['bg-slate-100', 'hover:bg-slate-200'],
            ColorToken::danger()->name() => ['bg-red-600', 'hover:bg-red-700'],
            ColorToken::surface()->name() => ['bg-white', 'dark:bg-slate-900'],
            ColorToken::background()->name() => ['bg-slate-50', 'dark:bg-slate-950'],
            ColorToken::textPrimary()->name() => ['text-slate-900', 'dark:text-slate-100'],
            ColorToken::textMuted()->name() => ['text-slate-500'],
            SpacingToken::small()->name() => ['p-4'],
            SpacingToken::medium()->name() => ['p-6'],
            SpacingToken::large()->name() => ['p-8'],
            RadiusToken::small()->name() => ['rounded'],
            RadiusToken::medium()->name() => ['rounded-md'],
            RadiusToken::large()->name() => ['rounded-lg'],
            RadiusToken::full()->name() => ['rounded-full'],
            ShadowToken::small()->name() => ['shadow-sm'],
            ShadowToken::medium()->name() => ['shadow'],
            ShadowToken::large()->name() => ['shadow-lg'],
            TypographyToken::body()->name() => ['text-base', 'font-normal'],
            TypographyToken::heading()->name() => ['text-lg', 'font-semibold'],
            TypographyToken::label()->name() => ['text-sm', 'font-medium'],
            TypographyToken::muted()->name() => ['text-slate-500'],
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function button(array $state): array
    {
        $variant = (string) ($state['variant'] ?? 'primary');
        $size = (string) ($state['size'] ?? 'md');
        $disabled = (bool) ($state['disabled'] ?? false);
        $rounded = (bool) ($state['rounded'] ?? false);

        $base = [
            'inline-flex',
            'items-center',
            'justify-center',
            'font-medium',
            'transition-colors',
            'focus-visible:outline',
            'focus-visible:outline-2',
            'focus-visible:outline-offset-2',
            'focus-visible:outline-sky-600',
        ];

        $sizes = [
            'sm' => ['text-sm', 'px-3', 'py-1.5'],
            'md' => ['text-sm', 'px-4', 'py-2'],
            'lg' => ['text-base', 'px-5', 'py-2.5'],
        ];

        $variants = [
            'primary' => $this->classMerger->merge($this->token(ColorToken::primary()), ['text-white']),
            'secondary' => $this->classMerger->merge($this->token(ColorToken::secondary()), [$this->token(ColorToken::textPrimary())[0]]),
            'danger' => $this->classMerger->merge($this->token(ColorToken::danger()), ['text-white']),
            'ghost' => ['bg-transparent', 'text-slate-700', 'hover:bg-slate-100'],
        ];

        if (! isset($sizes[$size], $variants[$variant])) {
            throw new InvalidArgumentException('Invalid button theme state.');
        }

        return $this->classMerger->merge(
            $base,
            $this->token($rounded ? RadiusToken::full() : RadiusToken::medium()),
            $sizes[$size],
            $variants[$variant],
            $disabled ? ['opacity-50', 'cursor-not-allowed', 'pointer-events-none'] : [],
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function text(array $state): array
    {
        $size = (string) ($state['size'] ?? 'md');
        $weight = (string) ($state['weight'] ?? 'normal');
        $tone = (string) ($state['tone'] ?? 'default');

        $sizes = [
            'xs' => 'text-xs',
            'sm' => 'text-sm',
            'md' => 'text-base',
            'lg' => 'text-lg',
            'xl' => 'text-xl',
            '2xl' => 'text-2xl',
        ];

        $weights = [
            'normal' => 'font-normal',
            'medium' => 'font-medium',
            'semibold' => 'font-semibold',
            'bold' => 'font-bold',
        ];

        $tones = [
            'default' => $this->token(ColorToken::textPrimary())[0],
            'muted' => $this->token(ColorToken::textMuted())[0],
            'primary' => 'text-sky-700',
            'danger' => 'text-red-700',
        ];

        if (! isset($sizes[$size], $weights[$weight], $tones[$tone])) {
            throw new InvalidArgumentException('Invalid text theme state.');
        }

        return [$sizes[$size], $weights[$weight], $tones[$tone]];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function card(array $state): array
    {
        $padding = (string) ($state['padding'] ?? 'md');

        $paddings = [
            'sm' => $this->token(SpacingToken::small()),
            'md' => $this->token(SpacingToken::medium()),
            'lg' => $this->token(SpacingToken::large()),
        ];

        if (! isset($paddings[$padding])) {
            throw new InvalidArgumentException('Invalid card theme state.');
        }

        return $this->classMerger->merge(
            $this->token(RadiusToken::large()),
            ['border', 'border-slate-200', 'dark:border-slate-700'],
            $this->token(ColorToken::surface()),
            $this->token(ShadowToken::small()),
            $paddings[$padding],
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function cardTitle(array $state): array
    {
        return $this->classMerger->merge(
            ['mb-4'],
            $this->token(TypographyToken::heading()),
            $this->token(ColorToken::textPrimary()),
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function container(array $state): array
    {
        $maxWidth = (string) ($state['maxWidth'] ?? 'lg');
        $padding = (string) ($state['padding'] ?? 'md');

        $maxWidths = [
            'sm' => 'max-w-screen-sm',
            'md' => 'max-w-screen-md',
            'lg' => 'max-w-screen-lg',
            'xl' => 'max-w-screen-xl',
            'full' => 'max-w-full',
        ];

        $paddings = [
            'none' => [],
            'sm' => ['px-4', 'py-4'],
            'md' => ['px-6', 'py-6'],
            'lg' => ['px-8', 'py-8'],
        ];

        if (! isset($maxWidths[$maxWidth], $paddings[$padding])) {
            throw new InvalidArgumentException('Invalid container theme state.');
        }

        return $this->classMerger->merge(
            ['mx-auto', 'w-full', $maxWidths[$maxWidth]],
            $paddings[$padding],
        );
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function row(array $state): array
    {
        $justify = (string) ($state['justify'] ?? 'start');
        $align = (string) ($state['align'] ?? 'stretch');
        $gap = $state['gap'] ?? null;

        $justifies = [
            'start' => 'justify-start',
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
            'around' => 'justify-around',
        ];

        $aligns = [
            'start' => 'items-start',
            'center' => 'items-center',
            'end' => 'items-end',
            'stretch' => 'items-stretch',
        ];

        if (! isset($justifies[$justify], $aligns[$align])) {
            throw new InvalidArgumentException('Invalid row theme state.');
        }

        $classes = ['flex', 'flex-row', 'w-full', $justifies[$justify], $aligns[$align]];

        if ($gap !== null) {
            $classes[] = $this->spacingClass('gap', $gap);
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function column(array $state): array
    {
        $align = (string) ($state['align'] ?? 'stretch');
        $gap = $state['gap'] ?? null;

        $aligns = [
            'start' => 'items-start',
            'center' => 'items-center',
            'end' => 'items-end',
            'stretch' => 'items-stretch',
        ];

        if (! isset($aligns[$align])) {
            throw new InvalidArgumentException('Invalid column theme state.');
        }

        $classes = ['flex', 'flex-col', 'w-full', $aligns[$align]];

        if ($gap !== null) {
            $classes[] = $this->spacingClass('gap', $gap);
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function center(array $state): array
    {
        return [
            'flex',
            'w-full',
            'items-center',
            'justify-center',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function padding(array $state): array
    {
        $classes = [];

        foreach (['all' => 'p', 'x' => 'px', 'y' => 'py', 'top' => 'pt', 'bottom' => 'pb', 'left' => 'pl', 'right' => 'pr'] as $key => $prefix) {
            if (array_key_exists($key, $state) && $state[$key] !== null) {
                $classes[] = $this->spacingClass($prefix, $state[$key]);
            }
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function spacer(array $state): array
    {
        return ['flex-1'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function expanded(array $state): array
    {
        return ['flex-1', 'min-w-0', 'min-h-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function sizedBox(array $state): array
    {
        $classes = [];

        if (array_key_exists('width', $state) && $state['width'] !== null) {
            $classes[] = $this->spacingClass('w', $state['width']);
        }

        if (array_key_exists('height', $state) && $state['height'] !== null) {
            $classes[] = $this->spacingClass('h', $state['height']);
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function stack(array $state): array
    {
        return ['relative', 'w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function divider(array $state): array
    {
        $orientation = (string) ($state['orientation'] ?? 'horizontal');
        $spacing = (string) ($state['spacing'] ?? 'md');

        $spacingMap = [
            'none' => ['my-0', 'mx-0'],
            'sm' => ['my-2', 'mx-2'],
            'md' => ['my-4', 'mx-4'],
            'lg' => ['my-6', 'mx-6'],
        ];

        if (! isset($spacingMap[$spacing])) {
            throw new InvalidArgumentException('Invalid divider spacing.');
        }

        if ($orientation === 'vertical') {
            return [
                'border-0',
                'border-l',
                'border-slate-200',
                'dark:border-slate-700',
                'h-auto',
                'self-stretch',
                'w-px',
                $spacingMap[$spacing][1],
            ];
        }

        return [
            'border-0',
            'border-t',
            'border-slate-200',
            'dark:border-slate-700',
            'w-full',
            $spacingMap[$spacing][0],
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function appBar(array $state): array
    {
        return [
            'flex',
            'w-full',
            'items-center',
            'justify-between',
            'gap-4',
            'px-4',
            'py-3',
            'bg-slate-900',
            'text-white',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function appBarTitle(array $state): array
    {
        return ['text-lg', 'font-semibold', 'tracking-tight'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function appBarActions(array $state): array
    {
        return ['flex', 'items-center', 'gap-2'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function sidebar(array $state): array
    {
        return [
            'flex',
            'flex-col',
            'gap-2',
            'p-4',
            'w-56',
            'min-h-full',
            'bg-slate-50',
            'dark:bg-slate-900',
            'border-r',
            'border-slate-200',
            'dark:border-slate-700',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function mainContent(array $state): array
    {
        return ['flex', 'flex-col', 'gap-4', 'p-6', 'w-full', 'min-w-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function footerBar(array $state): array
    {
        return [
            'w-full',
            'px-6',
            'py-4',
            'text-sm',
            'text-slate-500',
            'border-t',
            'border-slate-200',
            'dark:border-slate-700',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function form(array $state): array
    {
        return ['flex', 'flex-col', 'gap-4', 'w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function label(array $state): array
    {
        return ['block', 'text-sm', 'font-medium', 'text-slate-700', 'dark:text-slate-200', 'mb-1'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function input(array $state): array
    {
        return $this->controlField($state);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function textarea(array $state): array
    {
        return array_merge($this->controlField($state), ['min-h-[5rem]']);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function select(array $state): array
    {
        return $this->controlField($state);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function controlField(array $state): array
    {
        $classes = [
            'block',
            'w-full',
            'rounded-md',
            'border',
            'border-slate-300',
            'dark:border-slate-600',
            'bg-white',
            'dark:bg-slate-900',
            'px-3',
            'py-2',
            'text-sm',
            'text-slate-900',
            'dark:text-slate-100',
            'shadow-sm',
            'focus:outline-none',
            'focus:ring-2',
            'focus:ring-sky-500',
            'focus:border-sky-500',
        ];

        if (! empty($state['invalid'])) {
            $classes[] = 'border-rose-500';
            $classes[] = 'focus:ring-rose-500';
            $classes[] = 'focus:border-rose-500';
        }

        if (! empty($state['disabled'])) {
            $classes[] = 'opacity-60';
            $classes[] = 'cursor-not-allowed';
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function checkbox(array $state): array
    {
        return $this->choiceControl($state);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function radio(array $state): array
    {
        return $this->choiceControl($state);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function switchControl(array $state): array
    {
        return array_merge($this->choiceControl($state), ['accent-sky-600']);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function choiceControl(array $state): array
    {
        $classes = ['h-4', 'w-4', 'rounded', 'border-slate-300', 'text-sky-600', 'focus:ring-sky-500'];

        if (! empty($state['disabled'])) {
            $classes[] = 'opacity-60';
            $classes[] = 'cursor-not-allowed';
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function choiceWrap(array $state): array
    {
        return ['flex', 'items-center', 'gap-2'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function choiceLabel(array $state): array
    {
        return ['text-sm', 'text-slate-700', 'dark:text-slate-200'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function fieldGroup(array $state): array
    {
        return ['flex', 'flex-col', 'gap-1', 'w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function helperText(array $state): array
    {
        return ['text-xs', 'text-slate-500', 'dark:text-slate-400'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function errorText(array $state): array
    {
        return ['text-xs', 'font-medium', 'text-rose-600', 'dark:text-rose-400'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function link(array $state): array
    {
        $classes = [
            'text-sm',
            'font-medium',
            'text-sky-600',
            'hover:text-sky-700',
            'underline-offset-2',
            'hover:underline',
        ];

        if (! empty($state['active'])) {
            $classes[] = 'text-sky-800';
            $classes[] = 'font-semibold';
            $classes[] = 'underline';
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function scaffold(array $state): array
    {
        return ['flex', 'flex-col', 'min-h-full', 'w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function scaffoldAppBar(array $state): array
    {
        return ['w-full', 'shrink-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function scaffoldSidebar(array $state): array
    {
        return ['shrink-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function scaffoldMain(array $state): array
    {
        return ['flex', 'flex-1', 'w-full', 'min-h-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function scaffoldBody(array $state): array
    {
        return ['flex-1', 'min-w-0', 'min-h-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function scaffoldFooter(array $state): array
    {
        return ['w-full', 'shrink-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function table(array $state): array
    {
        $classes = ['w-full', 'text-left', 'text-sm', 'border-collapse'];

        if (! empty($state['compact'])) {
            $classes[] = 'text-xs';
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function tableHead(array $state): array
    {
        return ['bg-slate-50', 'dark:bg-slate-800'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function tableBody(array $state): array
    {
        return ['divide-y', 'divide-slate-200', 'dark:divide-slate-700'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function tableRow(array $state): array
    {
        return [];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function tableCell(array $state): array
    {
        return ['px-3', 'py-2', 'align-middle', 'text-slate-700', 'dark:text-slate-200'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function tableHeaderCell(array $state): array
    {
        return ['px-3', 'py-2', 'font-semibold', 'text-slate-900', 'dark:text-slate-100'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function dataTable(array $state): array
    {
        $classes = [
            'w-full',
            'rounded-lg',
            'border',
            'border-slate-200',
            'dark:border-slate-700',
            'bg-white',
            'dark:bg-slate-900',
            'overflow-hidden',
        ];

        if (! empty($state['striped'])) {
            $classes[] = '[&_tbody_tr:nth-child(odd)]:bg-slate-50';
            $classes[] = 'dark:[&_tbody_tr:nth-child(odd)]:bg-slate-800/50';
        }

        if (! empty($state['hover'])) {
            $classes[] = '[&_tbody_tr:hover]:bg-slate-100';
            $classes[] = 'dark:[&_tbody_tr:hover]:bg-slate-800';
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function dataTableScroll(array $state): array
    {
        return ['w-full', 'overflow-x-auto'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function dataTableCaption(array $state): array
    {
        return ['caption-top', 'px-3', 'py-2', 'text-left', 'text-sm', 'text-slate-500'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function searchField(array $state): array
    {
        return ['relative', 'flex', 'items-center', 'w-full', 'max-w-md'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function searchFieldInput(array $state): array
    {
        return array_merge($this->controlField($state), ['pr-3']);
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function searchFieldIcon(array $state): array
    {
        return ['absolute', 'left-3', 'text-slate-400', 'pointer-events-none'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function pagination(array $state): array
    {
        return ['w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function paginationList(array $state): array
    {
        return ['flex', 'flex-wrap', 'items-center', 'gap-1', 'list-none', 'p-0', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function paginationItem(array $state): array
    {
        return [];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function paginationLink(array $state): array
    {
        $classes = [
            'inline-flex',
            'items-center',
            'justify-center',
            'min-w-9',
            'h-9',
            'px-3',
            'rounded-md',
            'text-sm',
            'border',
            'border-slate-200',
            'dark:border-slate-700',
        ];

        if (! empty($state['current'])) {
            $classes[] = 'bg-sky-600';
            $classes[] = 'text-white';
            $classes[] = 'border-sky-600';
        }

        if (! empty($state['disabled'])) {
            $classes[] = 'opacity-50';
            $classes[] = 'cursor-not-allowed';
        }

        return $classes;
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function paginationEllipsis(array $state): array
    {
        return ['px-2', 'text-slate-400'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function avatar(array $state): array
    {
        $sizes = ['sm' => 'h-8 w-8 text-xs', 'md' => 'h-10 w-10 text-sm', 'lg' => 'h-14 w-14 text-base', 'xl' => 'h-20 w-20 text-lg'];
        $size = (string) ($state['size'] ?? 'md');
        $shape = (string) ($state['shape'] ?? 'circle');

        return [
            'inline-flex',
            'items-center',
            'justify-center',
            'overflow-hidden',
            'bg-slate-200',
            'dark:bg-slate-700',
            'text-slate-700',
            'dark:text-slate-100',
            'font-semibold',
            $sizes[$size] ?? $sizes['md'],
            $shape === 'square' ? 'rounded-md' : 'rounded-full',
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function avatarImage(array $state): array
    {
        return ['h-full', 'w-full', 'object-cover'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function avatarInitials(array $state): array
    {
        return ['leading-none'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function image(array $state): array
    {
        return ['max-w-full', 'h-auto'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function logo(array $state): array
    {
        return ['inline-flex', 'items-center', 'gap-2', 'font-bold', 'text-slate-900', 'dark:text-slate-100', 'no-underline'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function logoImage(array $state): array
    {
        return ['h-8', 'w-8', 'object-contain'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function logoText(array $state): array
    {
        return ['text-lg', 'tracking-tight'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function statCard(array $state): array
    {
        $colors = [
            'default' => 'border-slate-200 dark:border-slate-700',
            'primary' => 'border-sky-300',
            'success' => 'border-emerald-300',
            'warning' => 'border-amber-300',
            'danger' => 'border-rose-300',
        ];
        $color = (string) ($state['color'] ?? 'default');

        return [
            'flex',
            'flex-col',
            'gap-1',
            'rounded-lg',
            'border',
            'bg-white',
            'dark:bg-slate-900',
            'p-4',
            'shadow-sm',
            'min-w-[10rem]',
            'flex-1',
            $colors[$color] ?? $colors['default'],
        ];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function statCardIcon(array $state): array
    {
        return ['text-slate-400', 'mb-1'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function statCardTitle(array $state): array
    {
        return ['text-sm', 'text-slate-500'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function statCardValue(array $state): array
    {
        return ['text-2xl', 'font-bold', 'text-slate-900', 'dark:text-slate-100'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function statCardSubtitle(array $state): array
    {
        return ['text-xs', 'text-slate-500'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function statCardTrend(array $state): array
    {
        return ['text-xs', 'font-medium', 'text-emerald-600'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function metric(array $state): array
    {
        return ['inline-flex', 'flex-col', 'gap-0.5', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function metricLabel(array $state): array
    {
        return ['text-xs', 'text-slate-500', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function metricValue(array $state): array
    {
        return ['text-sm', 'font-semibold', 'text-slate-900', 'dark:text-slate-100', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function emptyState(array $state): array
    {
        return ['flex', 'flex-col', 'items-center', 'justify-center', 'gap-2', 'text-center', 'px-6', 'py-10'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function emptyStateIcon(array $state): array
    {
        return ['text-slate-400', 'text-3xl'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function emptyStateTitle(array $state): array
    {
        return ['text-lg', 'font-semibold', 'text-slate-900', 'dark:text-slate-100', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function emptyStateDescription(array $state): array
    {
        return ['text-sm', 'text-slate-500', 'm-0', 'max-w-sm'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function emptyStateAction(array $state): array
    {
        return ['mt-2'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityList(array $state): array
    {
        return ['flex', 'flex-col', 'gap-3', 'list-none', 'p-0', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityItem(array $state): array
    {
        return ['flex', 'gap-3', 'items-start'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityItemIcon(array $state): array
    {
        return ['mt-0.5', 'text-slate-400'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityItemBody(array $state): array
    {
        return ['flex', 'flex-col', 'gap-0.5', 'min-w-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityItemTitle(array $state): array
    {
        return ['text-sm', 'font-medium', 'text-slate-900', 'dark:text-slate-100'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityItemDescription(array $state): array
    {
        return ['text-sm', 'text-slate-500'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function activityItemTimestamp(array $state): array
    {
        return ['text-xs', 'text-slate-400'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function pageHeader(array $state): array
    {
        return ['flex', 'flex-wrap', 'items-start', 'justify-between', 'gap-4', 'w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function pageHeaderText(array $state): array
    {
        return ['flex', 'flex-col', 'gap-1', 'min-w-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function pageHeaderTitle(array $state): array
    {
        return ['text-2xl', 'font-bold', 'text-slate-900', 'dark:text-slate-100', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function pageHeaderSubtitle(array $state): array
    {
        return ['text-sm', 'text-slate-500', 'm-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function pageHeaderActions(array $state): array
    {
        return ['flex', 'items-center', 'gap-2', 'flex-shrink-0'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function toolbar(array $state): array
    {
        return ['flex', 'flex-wrap', 'items-center', 'justify-between', 'gap-3', 'w-full'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function toolbarLeading(array $state): array
    {
        return ['flex', 'items-center', 'gap-2', 'flex-1', 'min-w-[12rem]'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function toolbarCenter(array $state): array
    {
        return ['flex', 'items-center', 'gap-2'];
    }

    /**
     * @param array<string, scalar|bool|null> $state
     * @return list<string>
     */
    private function toolbarTrailing(array $state): array
    {
        return ['flex', 'items-center', 'gap-2'];
    }

    private function spacingClass(string $prefix, mixed $value): string
    {
        if (is_string($value) && in_array($value, ['sm', 'md', 'lg'], true)) {
            $map = ['sm' => 2, 'md' => 4, 'lg' => 6];

            return $prefix . '-' . $map[$value];
        }

        if (! is_int($value) && ! is_float($value) && ! (is_string($value) && is_numeric($value))) {
            throw new InvalidArgumentException(sprintf('Invalid spacing value for "%s".', $prefix));
        }

        $normalized = is_string($value) ? (str_contains($value, '.') ? (float) $value : (int) $value) : $value;

        if (! in_array($normalized, self::SPACING_SCALE, true)) {
            throw new InvalidArgumentException(sprintf(
                'Spacing value "%s" is not on the Tailwind scale.',
                (string) $value,
            ));
        }

        $token = is_float($normalized) ? (string) $normalized : (string) $normalized;

        return $prefix . '-' . $token;
    }
}
