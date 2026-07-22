<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\AuthUi\Components\RememberMeCheckbox;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;

/**
 * Sign-in card: email, password, remember-me, links to register / forgot password.
 *
 * @example LoginCard::make()->action('/login')->registerUrl('/register')->forgotUrl('/forgot')
 */
final class LoginCard extends AuthFormCard
{
    private string $registerUrl = '/register';

    private string $forgotUrl = '/forgot-password';

    private bool $remember = true;

    protected string $submitLabel = 'Sign in';

    public static function make(): self
    {
        return new self();
    }

    public function registerUrl(string $url): self
    {
        $this->registerUrl = $url;

        return $this;
    }

    public function forgotUrl(string $url): self
    {
        $this->forgotUrl = $url;

        return $this;
    }

    public function remember(bool $remember = true): self
    {
        $this->remember = $remember;

        return $this;
    }

    protected function defaultTitle(): string
    {
        return 'Sign in';
    }

    protected function defaultSubtitle(): string
    {
        return 'Enter your credentials to continue';
    }

    protected function defaultFooterLinks(): void
    {
        if ($this->footerLinkItems !== []) {
            return;
        }

        $this->footerLinks(
            $this->authLink('Create an account', $this->registerUrl),
            $this->authLink('Forgot password?', $this->forgotUrl),
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

        $form->field(
            Input::make('password')
                ->type('password')
                ->label('Password')
                ->autocomplete('current-password')
                ->required(),
        );

        if ($this->remember) {
            $checkbox = RememberMeCheckbox::make();

            if ($this->formStateBag !== null) {
                $checkbox->formState($this->formStateBag);
            }

            $form->add($checkbox);
        }
    }
}
