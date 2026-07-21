<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Accordion;
use Serixa\Component\AccordionItem;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\Select;
use Serixa\Component\SizedBox;
use Serixa\Component\SwitchControl;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class SettingsPage
{
    public static function document(): Document
    {
        $general = Column::make([
            Label::make('Workspace name')->for('workspace'),
            Input::make()
                ->id('workspace')
                ->name('workspace')
                ->placeholder('Acme Inc')
                ->value('SERIXA Admin'),
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
        ])->gap(2)->alignStart();

        $content = Form::make()
            ->method('POST')
            ->action('/?page=settings')
            ->child(
                Column::make([
                    PageHeader::make()
                        ->title('Settings')
                        ->subtitle('Workspace preferences with tabs and accordion.'),
                    SizedBox::height(2),
                    Tabs::make()
                        ->tab(Tab::make('General')->active()->content($general))
                        ->tab(
                            Tab::make('Appearance')->content(
                                Column::make([
                                    SwitchControl::make()->name('compact')->label('Compact density'),
                                    SwitchControl::make()->name('sidebar_collapsed')->label('Collapse sidebar by default'),
                                    Text::make('Toggles are progressive enhancement only.')
                                        ->size('sm')
                                        ->tone('muted'),
                                ])->gap(2),
                            ),
                        ),
                    SizedBox::height(4),
                    Accordion::make()
                        ->item(
                            AccordionItem::make()
                                ->title('Notifications')
                                ->open()
                                ->content(
                                    Column::make([
                                        SwitchControl::make()->name('email_digests')->label('Email digests'),
                                        SwitchControl::make()->name('push')->label('Push notifications'),
                                    ])->gap(2),
                                ),
                        )
                        ->item(
                            AccordionItem::make()
                                ->title('Security')
                                ->content(
                                    Text::make('Session timeout and 2FA policies would live here.'),
                                ),
                        ),
                    SizedBox::height(3),
                    Button::make('Save changes')->primary()->large()->type('submit'),
                ])->gap(2)->alignStart(),
            );

        return Document::make()
            ->title('Settings · Admin')
            ->body(AdminLayout::shell('settings', $content));
    }
}
