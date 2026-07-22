<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\AuthUi\Components\AuthAlert;
use Serixa\AuthUi\Components\AuthLink;
use Serixa\AuthUi\Components\BrandLogo;
use Serixa\AuthUi\Contracts\AuthWidgetInterface;
use Serixa\AuthUi\Layout\AuthCard;
use Serixa\AuthUi\Layout\AuthFooter;
use Serixa\AuthUi\Layout\AuthHeader;
use Serixa\AuthUi\Support\ResolvesAuthTheme;
use Serixa\Component\Button;
use Serixa\Component\Component;
use Serixa\Contracts\WidgetInterface;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Contracts\CsrfProvider;
use Serixa\Forms\State\FormState;

/**
 * Email verification notice with resend action (form submit or link).
 *
 * @example VerifyEmailCard::make()->message('Check your inbox')->action('/email/verification-notification')
 * @example VerifyEmailCard::make()->resendHref('/email/verify/resend')
 */
final class VerifyEmailCard extends Component implements AuthWidgetInterface
{
    use ResolvesAuthTheme;

    private ?string $titleText = null;

    private ?string $subtitleText = null;

    private string $messageText = 'Please verify your email address by clicking the link we sent you.';

    private string $actionUrl = '';

    private string $httpMethod = 'POST';

    private ?string $resendHref = null;

    private string $resendLabel = 'Resend verification';

    private ?FormState $formStateBag = null;

    private ?CsrfProvider $csrfProvider = null;

    private BrandLogo|string|null $brandSlot = null;

    /** @var list<WidgetInterface|string> */
    private array $footerLinkItems = [];

    /** @var list<WidgetInterface|string> */
    private array $extraChildren = [];

    private function __construct()
    {
        parent::__construct();
    }

    public static function make(): self
    {
        return new self();
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

    public function message(string $message): self
    {
        $this->messageText = $message;

        return $this;
    }

    public function action(string $url): self
    {
        $this->actionUrl = $url;

        return $this;
    }

    public function method(string $method = 'POST'): self
    {
        $this->httpMethod = strtoupper($method);

        return $this;
    }

    public function resendHref(string $href): self
    {
        $this->resendHref = $href;

        return $this;
    }

    public function resendLabel(string $label): self
    {
        $this->resendLabel = $label;

        return $this;
    }

    public function formState(FormState $state): self
    {
        $this->formStateBag = $state;

        return $this;
    }

    public function csrf(CsrfProvider $csrf): self
    {
        $this->csrfProvider = $csrf;

        return $this;
    }

    public function brand(BrandLogo|string|null $brand): self
    {
        $this->brandSlot = $brand;

        return $this;
    }

    /**
     * @param WidgetInterface|string|list<WidgetInterface|string> ...$links
     */
    public function footerLinks(WidgetInterface|string|array ...$links): self
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
    public function extra(WidgetInterface|string|array ...$children): self
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

    public function toWidget(): AuthCard
    {
        $header = AuthHeader::make();

        if ($this->brandSlot instanceof BrandLogo) {
            $header->child($this->brandSlot);
        } elseif (is_string($this->brandSlot) && $this->brandSlot !== '') {
            $header->child(BrandLogo::make($this->brandSlot));
        }

        $header->title($this->titleText ?? 'Verify your email');

        if ($this->subtitleText !== null && $this->subtitleText !== '') {
            $header->subtitle($this->subtitleText);
        }

        $cardChildren = [
            $header,
            AuthAlert::make($this->messageText),
            $this->buildResendAction(),
            ...$this->extraChildren,
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

    private function buildResendAction(): WidgetInterface
    {
        if ($this->resendHref !== null && $this->resendHref !== '') {
            return AuthLink::make($this->resendLabel)->href($this->resendHref);
        }

        if ($this->actionUrl !== '') {
            $form = FormBuilder::make()
                ->method($this->httpMethod)
                ->action($this->actionUrl);

            if ($this->csrfProvider !== null) {
                $form->csrf($this->csrfProvider);
            }

            if ($this->formStateBag !== null) {
                $form->state($this->formStateBag);
            }

            $form->submit($this->resendLabel);

            return $form->toWidget();
        }

        return Button::make($this->resendLabel)->type('button')->primary();
    }
}
