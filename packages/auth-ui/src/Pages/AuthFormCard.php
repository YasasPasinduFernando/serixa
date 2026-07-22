<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\AuthUi\Components\AuthLink;
use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Layout\AuthCard;
use Serixa\AuthUi\Layout\AuthFooter;
use Serixa\AuthUi\Layout\AuthHeader;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Contracts\CsrfProvider;
use Serixa\Forms\State\FormState;

/**
 * Shared fluent configuration for auth form page cards.
 *
 * @internal
 */
abstract class AuthFormCard extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    protected ?string $titleText = null;

    protected ?string $subtitleText = null;

    protected string $actionUrl = '';

    protected string $httpMethod = 'POST';

    protected ?FormState $formStateBag = null;

    protected ?CsrfProvider $csrfProvider = null;

    protected BrandLogo|string|null $brandSlot = null;

    /** @var list<WidgetInterface|string> */
    protected array $footerLinkItems = [];

    /** @var list<WidgetInterface|string> */
    protected array $extraChildren = [];

    protected bool $displaySummary = false;

    protected string $submitLabel = 'Submit';

    final protected function __construct()
    {
        parent::__construct();
    }

    public function title(string $title): static
    {
        $this->titleText = $title;

        return $this;
    }

    public function subtitle(string $subtitle): static
    {
        $this->subtitleText = $subtitle;

        return $this;
    }

    public function action(string $url): static
    {
        $this->actionUrl = $url;

        return $this;
    }

    public function method(string $method = 'POST'): static
    {
        $this->httpMethod = strtoupper($method);

        return $this;
    }

    public function formState(FormState $state): static
    {
        $this->formStateBag = $state;

        return $this;
    }

    public function csrf(CsrfProvider $csrf): static
    {
        $this->csrfProvider = $csrf;

        return $this;
    }

    public function brand(BrandLogo|string|null $brand): static
    {
        $this->brandSlot = $brand;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$links
     */
    public function footerLinks(WidgetInterface|string|array ...$links): static
    {
        $normalized = $links;

        if (count($normalized) === 1 && is_array($normalized[0])) {
            $normalized = $normalized[0];
        }

        foreach ($normalized as $link) {
            if ($link instanceof WidgetInterface || is_string($link)) {
                $this->footerLinkItems[] = $link;
            }
        }

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$children
     */
    public function extra(WidgetInterface|string|array ...$children): static
    {
        $normalized = $children;

        if (count($normalized) === 1 && is_array($normalized[0])) {
            $normalized = $normalized[0];
        }

        foreach ($normalized as $child) {
            if ($child instanceof WidgetInterface || is_string($child)) {
                $this->extraChildren[] = $child;
            }
        }

        return $this;
    }

    public function showSummary(bool $show = true): static
    {
        $this->displaySummary = $show;

        return $this;
    }

    public function submitLabel(string $label): static
    {
        $this->submitLabel = $label;

        return $this;
    }

    /**
     * Populate the given form builder with card-specific fields (before submit).
     */
    abstract protected function configureForm(FormBuilder $form): void;

    /**
     * Default title when {@see title()} was not called.
     */
    abstract protected function defaultTitle(): string;

    /**
     * Optional default subtitle.
     */
    protected function defaultSubtitle(): ?string
    {
        return null;
    }

    /**
     * Hook for subclasses to append default footer links before render.
     */
    protected function defaultFooterLinks(): void
    {
    }

    /**
     * Build the composed {@see AuthCard} widget tree.
     */
    public function toWidget(): AuthCard
    {
        $this->defaultFooterLinks();

        $form = FormBuilder::make()
            ->method($this->httpMethod);

        if ($this->actionUrl !== '') {
            $form->action($this->actionUrl);
        }

        if ($this->csrfProvider !== null) {
            $form->csrf($this->csrfProvider);
        }

        if ($this->formStateBag !== null) {
            $form->state($this->formStateBag);
        }

        if (
            $this->displaySummary
            && $this->formStateBag !== null
            && $this->formStateBag->errors()->any()
        ) {
            $form->add(ValidationSummary::make($this->formStateBag));
        }

        $this->configureForm($form);
        $form->submit($this->submitLabel);

        foreach ($this->extraChildren as $child) {
            $form->add($child);
        }

        $header = AuthHeader::make();

        if ($this->brandSlot instanceof BrandLogo) {
            $header->child($this->brandSlot);
        } elseif (is_string($this->brandSlot) && $this->brandSlot !== '') {
            $header->child(BrandLogo::make($this->brandSlot));
        }

        $header->title($this->titleText ?? $this->defaultTitle());

        $subtitle = $this->subtitleText ?? $this->defaultSubtitle();

        if ($subtitle !== null && $subtitle !== '') {
            $header->subtitle($subtitle);
        }

        $cardChildren = [
            $header,
            $form->toWidget(),
        ];

        if ($this->footerLinkItems !== []) {
            $cardChildren[] = AuthFooter::make(...$this->footerLinkItems);
        }

        return AuthCard::make(...$cardChildren);
    }

    public function render(): string
    {
        return $this->toWidget()->render();
    }

    public function childrenForTree(): array
    {
        return [$this->toWidget()];
    }

    /**
     * Build a themed text link for footer / inline auth navigation.
     */
    protected function authLink(string $label, string $href): AuthLink
    {
        return AuthLink::make($label)->href($href);
    }
}
