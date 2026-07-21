<?php

declare(strict_types=1);

namespace App\Pages;

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
use Serixa\Component\PasswordInput;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class LoginPage
{
    public static function document(): Document
    {
        $form = Form::make()
            ->method('POST')
            ->action('/?page=login')
            ->child(
                Card::make(
                    Text::make('Admin')->as('h1')->size('xl')->weight('bold'),
                    Text::make('Sign in to continue.')->tone('muted')->size('sm'),
                    SizedBox::height(4),
                    Label::make('Email')->for('email'),
                    Input::make()
                        ->id('email')
                        ->name('email')
                        ->type('email')
                        ->placeholder('admin@example.com')
                        ->autocomplete('email')
                        ->required(),
                    Label::make('Password')->for('password'),
                    PasswordInput::make()
                        ->id('password')
                        ->name('password')
                        ->required()
                        ->minLength(6),
                    Checkbox::make()->name('remember')->label('Remember me'),
                    Button::make('Sign in')->primary()->large()->type('submit'),
                    Link::make('Back to dashboard')->href('/?page=dashboard'),
                )->title('Sign in')->padding('lg'),
            );

        $body = Container::make(
            Center::make(
                Column::make([
                    $form,
                ])->gap(2)->alignCenter(),
            ),
        )->maxWidth('sm');

        return Document::make()
            ->title('Login · Admin')
            ->body($body);
    }
}
