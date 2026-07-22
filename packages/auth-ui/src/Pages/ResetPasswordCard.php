<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;

/**
 * Reset-password card: email (visible or hidden), password, confirmation.
 *
 * @example ResetPasswordCard::make()->action('/reset-password')->email('a@b.c')->hideEmail()
 */
final class ResetPasswordCard extends AuthFormCard
{
    private string $loginUrl = '/login';

    private ?string $emailValue = null;

    private bool $hideEmail = false;

    protected string $submitLabel = 'Reset password';

    public static function make(): self
    {
        return new self();
    }

    public function loginUrl(string $url): self
    {
        $this->loginUrl = $url;

        return $this;
    }

    public function email(?string $email): self
    {
        $this->emailValue = $email;

        return $this;
    }

    public function hideEmail(bool $hide = true): self
    {
        $this->hideEmail = $hide;

        return $this;
    }

    protected function defaultTitle(): string
    {
        return 'Reset password';
    }

    protected function defaultSubtitle(): string
    {
        return 'Choose a new password for your account';
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
        $email = Input::make('email')
            ->type($this->hideEmail ? 'hidden' : 'email')
            ->autocomplete('email');

        if (! $this->hideEmail) {
            $email->label('Email')->required()->email();
        }

        if ($this->emailValue !== null) {
            $email->value($this->emailValue);
        }

        $form->field($email);

        $form->field(
            Input::make('password')
                ->type('password')
                ->label('Password')
                ->autocomplete('new-password')
                ->required()
                ->confirmed(),
        );

        $form->field(
            Input::make('password_confirmation')
                ->type('password')
                ->label('Confirm password')
                ->autocomplete('new-password')
                ->required(),
        );
    }
}
