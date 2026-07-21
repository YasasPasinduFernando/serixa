<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Center;
use Serixa\Component\Checkbox;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Link;
use Serixa\Component\Logo;
use Serixa\Component\PasswordInput;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;

final class LoginPage
{
    public static function document(): Document
    {
        $form = Form::make()
            ->method('POST')
            ->action('/?page=login')
            ->child(
                Card::make(
                    Logo::make()->text('SERIXA')->href('/?page=login'),
                    Text::make('Sign in')->as('h1')->size('xl')->weight('bold'),
                    Text::make('Use your SERIXA account to continue.')->tone('muted')->size('sm'),
                    SizedBox::height(4),
                    Label::make('Email')->for('email'),
                    Input::make()
                        ->id('email')
                        ->name('email')
                        ->type('email')
                        ->placeholder('Enter your email')
                        ->required(),
                    Label::make('Password')->for('password'),
                    PasswordInput::make()
                        ->id('password')
                        ->name('password')
                        ->required(),
                    Checkbox::make()
                        ->name('remember')
                        ->label('Remember me'),
                    Button::make('Login')->primary()->large()->type('submit'),
                    Link::make('Forgot password?')->href('/?page=login&forgot=1'),
                )->title('Welcome')->padding('lg'),
            );

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
                        Link::make('Continue to dashboard →')->href('/?page=dashboard'),
                    ])->gap(2)->alignCenter(),
                ),
            )->maxWidth('sm'),
        );
    }
}
