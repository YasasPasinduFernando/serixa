<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Center;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;

final class LoginPage
{
    public static function document(): Document
    {
        $form = Card::make(
            // Missing: Logo / Brand / Image
            Missing::widget('Logo', 'Product mark above the login form'),
            Text::make('Sign in')->as('h1')->size('xl')->weight('bold'),
            Text::make('Use your SERIXA account to continue.')->tone('muted')->size('sm'),
            SizedBox::height(4),
            // Missing: Input (email)
            Missing::widget('Input', 'Email address field'),
            // Missing: Input type=password
            Missing::widget('PasswordInput', 'Password field with optional visibility toggle'),
            // Missing: Checkbox
            Missing::widget('Checkbox', 'Remember me'),
            Button::make('Login')->primary()->large()->type('submit'),
            // Missing: Link — approximated awkwardly with Text::as('a')
            Text::make('Forgot password?')
                ->as('a')
                ->size('sm')
                ->tone('primary')
                ->attribute('href', '/?page=login&forgot=1'),
        )->title('Welcome')->padding('lg');

        // DX: No Form widget — cannot wrap controls in <form> without raw HTML
        // (and string children are HTML-escaped by the renderer).

        return DocumentFactory::page(
            'Login',
            Container::make(
                Center::make(
                    Column::make([
                        $form,
                        SizedBox::height(4),
                        Text::make('Demo navigation:')
                            ->size('sm')
                            ->tone('muted'),
                        Text::make('Continue to dashboard →')
                            ->as('a')
                            ->size('sm')
                            ->tone('primary')
                            ->attribute('href', '/?page=dashboard'),
                    ])->gap(2)->alignCenter(),
                ),
            )->maxWidth('sm'),
        );
    }
}
