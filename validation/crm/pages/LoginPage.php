<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Checkbox;
use Serixa\Component\Column;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\PasswordInput;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * CRM login form (SSR stub — no auth backend).
 */
final class LoginPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Sign in')
                ->subtitle('Access your CRM workspace'),
            Card::make(
                Form::make()
                    ->method('POST')
                    ->action('/?page=dashboard')
                    ->child(
                        Column::make([
                            Text::make('Use any credentials — this is a render-only stub.')
                                ->tone('muted')
                                ->size('sm'),
                            Label::make('Email')->for('login_email'),
                            Input::make()
                                ->id('login_email')
                                ->name('email')
                                ->type('email')
                                ->placeholder('you@company.example')
                                ->required(),
                            Label::make('Password')->for('login_password'),
                            PasswordInput::make()
                                ->id('login_password')
                                ->name('password')
                                ->required(),
                            Checkbox::make()
                                ->name('remember')
                                ->label('Remember me'),
                            Button::make('Sign in')->primary()->type('submit'),
                        ])->gap(3),
                    ),
            )->padding('lg'),
        ])->gap(4);

        return Document::make()
            ->title('Login · Serixa CRM')
            ->description('Login')
            ->language('en')
            ->body(CrmLayout::shell('login', $body));
    }
}
