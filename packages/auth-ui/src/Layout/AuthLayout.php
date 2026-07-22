<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Layout;

use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Support\Html;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;

/**
 * Full-page auth shell: centered card, or split layout when an aside / illustration is set.
 *
 * @example AuthLayout::make()->brand($logo)->card($loginCard)->footer($links)
 * @example AuthLayout::make()->aside($copy)->content($loginCard)
 */
final class AuthLayout extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private WidgetInterface|string|null $brandSlot = null;

    private WidgetInterface|string|null $headerSlot = null;

    private ?WidgetInterface $asideSlot = null;

    private ?WidgetInterface $illustrationSlot = null;

    private ?WidgetInterface $cardSlot = null;

    private WidgetInterface|string|null $contentSlot = null;

    private WidgetInterface|string|null $footerSlot = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
    }

    public function brand(WidgetInterface|string|null $brand): self
    {
        $this->brandSlot = $brand;

        return $this;
    }

    public function header(WidgetInterface|string|null $header): self
    {
        $this->headerSlot = $header;

        return $this;
    }

    public function aside(WidgetInterface|string|null $aside): self
    {
        if (is_string($aside)) {
            $aside = AuthAside::make($aside);
        }

        $this->asideSlot = $aside;

        return $this;
    }

    public function illustration(WidgetInterface|string|null $illustration): self
    {
        if (is_string($illustration)) {
            $illustration = AuthIllustration::make($illustration);
        }

        $this->illustrationSlot = $illustration;

        return $this;
    }

    public function card(WidgetInterface|string|null $card): self
    {
        if (is_string($card)) {
            $card = AuthCard::make($card);
        }

        $this->cardSlot = $card;

        return $this;
    }

    public function content(WidgetInterface|string|null $content): self
    {
        $this->contentSlot = $content;

        return $this;
    }

    public function footer(WidgetInterface|string|null $footer): self
    {
        $this->footerSlot = $footer;

        return $this;
    }

    public function childrenForTree(): array
    {
        $nodes = [];

        foreach ([
            $this->brandSlot,
            $this->headerSlot,
            $this->asideSlot,
            $this->illustrationSlot,
            $this->cardSlot,
            $this->contentSlot,
            $this->footerSlot,
        ] as $slot) {
            if ($slot !== null) {
                $nodes[] = $slot;
            }
        }

        return $nodes;
    }

    public function render(): string
    {
        $split = $this->asideSlot !== null || $this->illustrationSlot !== null;
        $main = $this->cardSlot ?? $this->contentSlot;

        $panel = Html::join(
            $this->renderOptional($this->brandSlot),
            $this->renderOptional($this->headerSlot),
            $this->renderOptional($main),
            $this->renderOptional($this->footerSlot),
        );

        if ($split) {
            $side = Html::join(
                $this->renderOptional($this->asideSlot),
                $this->renderOptional($this->illustrationSlot),
            );

            $mainPanel = sprintf(
                '<div%s>%s</div>',
                $this->buildStructuralAttributeString($this->theme()->classes('auth.layout.panel')),
                $panel,
            );

            $attrs = $this->buildAttributeString(
                $this->theme()->classes('auth.layout', ['split' => true]),
            );

            return Html::tag('div', $attrs, $side . $mainPanel);
        }

        $attrs = $this->buildAttributeString(
            $this->theme()->classes('auth.layout', ['split' => false]),
        );

        return Html::tag('div', $attrs, $panel);
    }

    private function renderOptional(WidgetInterface|string|null $slot): string
    {
        if ($slot === null) {
            return '';
        }

        return $this->renderChild($slot);
    }
}
