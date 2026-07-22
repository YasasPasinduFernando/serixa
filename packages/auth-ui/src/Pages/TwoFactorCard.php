<?php

declare(strict_types=1);

namespace Serixa\AuthUi\Pages;

use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Field\Input;

/**
 * Two-factor authentication code card.
 *
 * @example TwoFactorCard::make()->action('/two-factor')
 */
final class TwoFactorCard extends AuthFormCard
{
    protected string $submitLabel = 'Verify';

    public static function make(): self
    {
        return new self();
    }

    protected function defaultTitle(): string
    {
        return 'Two-factor authentication';
    }

    protected function defaultSubtitle(): string
    {
        return 'Enter the verification code from your authenticator app';
    }

    protected function configureForm(FormBuilder $form): void
    {
        $form->field(
            Input::make('code')
                ->type('text')
                ->label('Authentication code')
                ->autocomplete('one-time-code')
                ->required(),
        );
    }
}
