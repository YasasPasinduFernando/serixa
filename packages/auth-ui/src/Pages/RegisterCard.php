<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\Field\Input;

/**
 * Registration card: name, email, password, confirmation, terms.
 *
 * @example RegisterCard::make()->action('/register')->loginUrl('/login')
 */
final class RegisterCard extends AuthFormCard
{
    private string $loginUrl = '/login';

    private string $termsLabel = 'I agree to the terms of service';

    protected string $submitLabel = 'Create account';

    public static function make(): self
    {
        return new self();
    }

    public function loginUrl(string $url): self
    {
        $this->loginUrl = $url;

        return $this;
    }

    public function termsLabel(string $label): self
    {
        $this->termsLabel = $label;

        return $this;
    }

    protected function defaultTitle(): string
    {
        return 'Create account';
    }

    protected function defaultSubtitle(): string
    {
        return 'Start with a free account';
    }

    protected function defaultFooterLinks(): void
    {
        if ($this->footerLinkItems !== []) {
            return;
        }

        $this->footerLinks(
            $this->authLink('Already have an account? Sign in', $this->loginUrl),
        );
    }

    protected function configureForm(FormBuilder $form): void
    {
        $form->field(
            Input::make('name')
                ->type('text')
                ->label('Name')
                ->autocomplete('name')
                ->required(),
        );

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

        $terms = Checkbox::make('terms')
            ->label($this->termsLabel)
            ->accepted();

        if ($this->formStateBag !== null) {
            $terms->formState($this->formStateBag);
        }

        $form->field($terms);
    }
}
