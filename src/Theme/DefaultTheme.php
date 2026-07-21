<?php

declare(strict_types=1);

namespace NovaPHP\Theme;

use NovaPHP\Contracts\ThemeInterface;
use NovaPHP\Exception\InvalidArgumentException;
use NovaPHP\Support\ClassMerger;
use NovaPHP\Token\ColorToken;
use NovaPHP\Token\DesignToken;
use NovaPHP\Token\RadiusToken;
use NovaPHP\Token\ShadowToken;
use NovaPHP\Token\SpacingToken;
use NovaPHP\Token\TypographyToken;

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
        return [
            'border-0',
            'border-t',
            'border-slate-200',
            'dark:border-slate-700',
            'w-full',
            'my-4',
        ];
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
