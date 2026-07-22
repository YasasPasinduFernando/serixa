<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Layout;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Component\Concerns\HasChildren;
use Serixa\Contracts\WidgetInterface;

/**
 * Themed wrapper for auth page header content (brand, title, subtitle).
 *
 * @example AuthHeader::make(BrandLogo::make())->title('Welcome back')->subtitle('Sign in to continue')
 */
final class AuthHeader extends Component implements AuthWidgetInterface
{
    use HasChildren;
    use ResolvesAuthTheme;

    private ?string $titleText = null;

    private ?string $subtitleText = null;

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public static function make(WidgetInterface|string|array ...$children): self
    {
        $header = new self();
        $header->setChildren(self::normalizeChildren($children));

        return $header;
    }

    public function title(string $title): self
    {
        $this->titleText = $title;

        return $this;
    }

    public function subtitle(string $subtitle): self
    {
        $this->subtitleText = $subtitle;

        return $this;
    }

    public function render(): string
    {
        $inner = $this->renderChildren($this->getChildren());

        if ($this->titleText !== null) {
            $inner .= Html::tag(
                'h1',
                $this->buildStructuralAttributeString($this->theme()->classes('auth.title')),
                $this->escape($this->titleText),
            );
        }

        if ($this->subtitleText !== null) {
            $inner .= Html::tag(
                'p',
                $this->buildStructuralAttributeString($this->theme()->classes('auth.subtitle')),
                $this->escape($this->subtitleText),
            );
        }

        $attrs = $this->buildAttributeString($this->theme()->classes('auth.header'));

        return Html::tag('header', $attrs, $inner);
    }
}
