<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\Select;
use Serixa\Component\SwitchControl;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Settings with tabs, form fields, and switch controls.
 */
final class SettingsPage
{
    public static function document(): Document
    {
        $general = Form::make()
            ->method('POST')
            ->action('/?page=settings')
            ->child(
                Column::make([
                    Label::make('Workspace name')->for('workspace'),
                    Input::make()
                        ->id('workspace')
                        ->name('workspace')
                        ->placeholder('Serixa CRM'),
                    Label::make('Default currency')->for('currency'),
                    Select::make()
                        ->id('currency')
                        ->name('currency')
                        ->placeholder('Choose currency')
                        ->options([
                            'usd' => 'USD',
                            'eur' => 'EUR',
                            'gbp' => 'GBP',
                        ]),
                    Button::make('Save general')->primary()->type('submit'),
                ])->gap(3),
            );

        $notifications = Form::make()
            ->method('POST')
            ->action('/?page=settings')
            ->child(
                Column::make([
                    SwitchControl::make()
                        ->name('email_digests')
                        ->label('Email digests')
                        ->checked(),
                    SwitchControl::make()
                        ->name('deal_alerts')
                        ->label('Deal stage alerts'),
                    SwitchControl::make()
                        ->name('invoice_reminders')
                        ->label('Invoice reminders')
                        ->checked(),
                    Button::make('Save notifications')->primary()->type('submit'),
                ])->gap(3),
            );

        $body = Column::make([
            PageHeader::make()
                ->title('Settings')
                ->subtitle('Workspace preferences'),
            Tabs::make()
                ->tab(Tab::make('General')->content($general)->active())
                ->tab(Tab::make('Notifications')->content($notifications))
                ->tab(
                    Tab::make('About')->content(
                        Text::make('Serixa CRM validation app · public API only.'),
                    ),
                ),
        ])->gap(4);

        return Document::make()
            ->title('Settings · Serixa CRM')
            ->description('Settings')
            ->language('en')
            ->body(CrmLayout::shell('settings', $body));
    }
}
