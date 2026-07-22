<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Components;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Icons\Component\Icon;

/**
 * Brand wordmark (and optional icon) for auth surfaces.
 *
 * @example BrandLogo::make('SERIXA')->href('/')->icon('shield-check')
 */
final class BrandLogo extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private string $text;

    private ?string $href = null;

    private ?string $iconName = null;

    private function __construct(string $text)
    {
        parent::__construct();
        $this->text = $text;
    }

    public static function make(string $text = 'SERIXA'): self
    {
        return new self($text);
    }

    public function href(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function icon(?string $iconName): self
    {
        $this->iconName = $iconName;

        return $this;
    }

    public function childrenForTree(): array
    {
        if ($this->iconName === null) {
            return [];
        }

        return [Icon::make($this->iconName)->size('md')->decorative()];
    }

    public function render(): string
    {
        $iconHtml = '';

        if ($this->iconName !== null) {
            $iconHtml = Icon::make($this->iconName)->size('md')->decorative()->render();
        }

        $inner = Html::join(
            $iconHtml,
            sprintf('<span>%s</span>', $this->escape($this->text)),
        );

        $attrs = $this->buildAttributeString($this->theme()->classes('auth.brand'));

        if ($this->href !== null) {
            return sprintf(
                '<a%s href="%s">%s</a>',
                $attrs,
                $this->escape($this->href),
                $inner,
            );
        }

        return Html::tag('div', $attrs, $inner);
    }
}
