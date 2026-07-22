<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;

/**
 * Forgot-password card: email + send reset link.
 *
 * @example ForgotPasswordCard::make()->action('/forgot-password')->loginUrl('/login')
 */
final class ForgotPasswordCard extends AuthFormCard
{
    private string $loginUrl = '/login';

    protected string $submitLabel = 'Send reset link';

    public static function make(): self
    {
        return new self();
    }

    public function loginUrl(string $url): self
    {
        $this->loginUrl = $url;

        return $this;
    }

    protected function defaultTitle(): string
    {
        return 'Forgot password';
    }

    protected function defaultSubtitle(): string
    {
        return 'Enter your email and we will send a reset link';
    }

    protected function defaultFooterLinks(): void
    {
        if ($this->footerLinkItems !== []) {
            return;
        }

        $this->footerLinks(
            $this->authLink('Back to sign in', $this->loginUrl),
        );
    }

    protected function configureForm(FormBuilder $form): void
    {
        $form->field(
            Input::make('email')
                ->type('email')
                ->label('Email')
                ->autocomplete('email')
                ->required()
                ->email(),
        );
    }
}
