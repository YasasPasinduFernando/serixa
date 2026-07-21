<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Link;
use Serixa\Component\Logo;
use Serixa\Component\PasswordInput;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class LoginPage
{
    public static function document(): Document
    {
        $login = Column::make([
            Logo::make()->text('SERIXA Journal')->href('/?page=home'),
            Text::make('Welcome back')->as('h1')->size('2xl')->weight('bold'),
            Text::make('Sign in to manage your profile and saved stories.')->tone('muted'),
            Form::make(Column::make([
                FieldGroup::make(
                    Label::make('Email')->for('login-email'),
                    Input::make()->id('login-email')->name('email')->type('email')->autocomplete('email')->required(),
                ),
                FieldGroup::make(
                    Label::make('Password')->for('login-password'),
                    PasswordInput::make()->id('login-password')->name('password')->minLength(8)->required(),
                ),
                Button::make('Sign in')->type('submit')->primary(),
            ])->gap(4))->method('POST')->action('/?page=login'),
            Link::make('Forgot your password?')->href('/?page=contact'),
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('Login · SERIXA Journal')
            ->body(SiteLayout::shell('login', Column::make(
                Card::make($login)->title('Member access')->padding('lg'),
            )->alignCenter()));
    }
}
