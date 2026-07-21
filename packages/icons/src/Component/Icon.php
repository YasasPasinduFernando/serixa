<?php

declare(strict_types=1);

namespace Serixa\Icons\Component;

use Serixa\Component\Component;
use Serixa\Component\Concerns\AssertsOption;
use Serixa\Component\Concerns\ResolvesTheme;
use Serixa\Icons\Registry\IconRegistry;
use Serixa\Icons\Support\SvgRenderer;

/**
 * Official SERIXA icon widget — inline SVG, theme-aware, SSR-only.
 *
 * @example Icon::make('search')->size('lg')->color('primary')
 * @example Icon::make('home')->decorative()
 * @example Icon::make('mail')->ariaLabel('Email')
 */
final class Icon extends Component
{
    use AssertsOption;
    use ResolvesTheme;

    private string $name;

    private ?string $pack = null;

    private string $size = 'md';

    private string $color = 'inherit';

    private ?string $ariaLabel = null;

    private ?string $title = null;

    private ?bool $decorative = null;

    private ?float $strokeWidth = null;

    private bool $filled = false;

    private function __construct(string $name)
    {
        parent::__construct();
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        $pack = null;
        $icon = $name;

        if (str_contains($name, ':')) {
            [$pack, $icon] = explode(':', $name, 2);
        }

        $instance = new self($icon);

        if ($pack !== null && $pack !== '') {
            $instance->pack = $pack;
        }

        return $instance;
    }

    public function pack(string $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    public function size(string $size): self
    {
        $this->assertOption($size, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'], 'size');
        $this->size = $size;

        return $this;
    }

    public function color(string $color): self
    {
        $this->assertOption(
            $color,
            ['primary', 'secondary', 'success', 'danger', 'warning', 'muted', 'inherit'],
            'color',
        );
        $this->color = $color;

        return $this;
    }

    public function ariaLabel(string $label): self
    {
        $this->ariaLabel = $label;
        $this->decorative = false;

        return $this;
    }

    public function decorative(bool $decorative = true): self
    {
        $this->decorative = $decorative;

        if ($decorative) {
            $this->ariaLabel = null;
        }

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        $this->decorative = false;

        return $this;
    }

    public function strokeWidth(float|int $width): self
    {
        $this->strokeWidth = (float) $width;

        return $this;
    }

    public function filled(): self
    {
        $this->filled = true;

        return $this;
    }

    public function outlined(): self
    {
        $this->filled = false;

        return $this;
    }

    public function render(): string
    {
        $resolved = IconRegistry::default()->resolve($this->name, $this->pack);
        $pack = $resolved['pack'];
        $stroke = $this->strokeWidth ?? $pack->defaultStrokeWidth();

        $classes = $this->theme()->classes('icon', [
            'size' => $this->size,
            'color' => $this->color,
        ]);

        $isDecorative = $this->isDecorative();

        if ($isDecorative) {
            $this->attribute('aria-hidden', 'true');
            $this->attribute('focusable', 'false');
            $this->attribute('role', null);
            $this->attribute('aria-label', null);
        } else {
            $this->attribute('aria-hidden', null);
            $this->attribute('focusable', null);
            $this->attribute('role', 'img');
            $label = $this->ariaLabel ?? $this->title ?? $this->name;
            $this->attribute('aria-label', $label);
        }

        $attrs = $this->buildAttributeString($classes);

        return SvgRenderer::render(
            $resolved['viewBox'],
            $resolved['paths'],
            $attrs,
            $this->filled,
            $stroke,
            $isDecorative ? null : ($this->title ?? null),
        );
    }

    private function isDecorative(): bool
    {
        if ($this->decorative !== null) {
            return $this->decorative;
        }

        return $this->ariaLabel === null && ($this->title === null || $this->title === '');
    }
}
