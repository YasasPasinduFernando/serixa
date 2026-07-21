<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Checkbox;
use Serixa\Component\Column;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Select;
use Serixa\Component\SizedBox;
use Serixa\Component\SwitchControl;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Shell;

final class SettingsPage
{
    public static function document(): Document
    {
        $content = Form::make()
            ->method('POST')
            ->action('/?page=settings')
            ->child(
                Column::make([
                    Text::make('Settings')->as('h1')->size('2xl')->weight('bold'),
                    Text::make('Workspace preferences.')->tone('muted'),
                    SizedBox::height(2),
                    Card::make(
                        Text::make('General')->as('h2')->size('lg')->weight('semibold'),
                        Label::make('Workspace name')->for('workspace'),
                        Input::make()
                            ->id('workspace')
                            ->name('workspace')
                            ->placeholder('Acme Inc')
                            ->value('SERIXA Demo'),
                        Label::make('Default timezone')->for('timezone'),
                        Select::make()
                            ->id('timezone')
                            ->name('timezone')
                            ->placeholder('Choose timezone')
                            ->options([
                                'UTC' => 'UTC',
                                'America/New_York' => 'America/New_York',
                                'Europe/London' => 'Europe/London',
                                'Asia/Colombo' => 'Asia/Colombo',
                            ])
                            ->value('UTC'),
                    )->padding('md'),
                    SizedBox::height(3),
                    Card::make(
                        Text::make('Appearance')->as('h2')->size('lg')->weight('semibold'),
                        SwitchControl::make()
                            ->name('dark_mode')
                            ->label('Dark mode'),
                        Text::make('Toggle is render-only; WidgetContext colorMode wiring is a later milestone.')
                            ->size('sm')
                            ->tone('muted'),
                    )->padding('md'),
                    SizedBox::height(3),
                    Card::make(
                        Text::make('Notifications')->as('h2')->size('lg')->weight('semibold'),
                        Checkbox::make()->name('email_digests')->label('Email digests')->checked(),
                        Checkbox::make()->name('product_updates')->label('Product updates'),
                        SwitchControl::make()->name('push')->label('Push notifications'),
                    )->padding('md'),
                    SizedBox::height(3),
                    Button::make('Save changes')->primary()->large()->type('submit'),
                ])->gap(2)->alignStart(),
            );

        return DocumentFactory::page(
            'Settings',
            Shell::app('settings', $content),
        );
    }
}
