<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;

/**
 * Magic-link sign-in card: email + send link.
 *
 * @example MagicLinkCard::make()->action('/magic-link')->loginUrl('/login')
 */
final class MagicLinkCard extends AuthFormCard
{
    private string $loginUrl = '/login';

    protected string $submitLabel = 'Email magic link';

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
        return 'Magic link';
    }

    protected function defaultSubtitle(): string
    {
        return 'We will email you a link to sign in instantly';
    }

    protected function defaultFooterLinks(): void
    {
        if ($this->footerLinkItems !== []) {
            return;
        }

        $this->footerLinks(
            $this->authLink('Sign in with password', $this->loginUrl),
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
