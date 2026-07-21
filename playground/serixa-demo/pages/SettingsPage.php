<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Accordion;
use Serixa\Component\AccordionItem;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Select;
use Serixa\Component\SizedBox;
use Serixa\Component\SwitchControl;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\Components\PageSection;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Shell;

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
        ])->gap(2)->alignStart();

        $form = Form::make()
            ->method('POST')
            ->action('/?page=settings')
            ->child(
                Column::make([
                    Tabs::make()
                        ->tab(Tab::make('General')->active()->content($general))
                        ->tab(
                            Tab::make('Appearance')->content(
                                Column::make([
                                    SwitchControl::make()->name('dark_mode')->label('Dark mode'),
                                    Text::make('Toggle is progressive enhancement only.')
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
                                ->content(
                                    Column::make([
                                        SwitchControl::make()->name('email_digests')->label('Email digests'),
                                        SwitchControl::make()->name('push')->label('Push notifications'),
                                    ])->gap(2),
                                )
                                ->open(),
                        )
                        ->item(
                            AccordionItem::make()
                                ->title('Privacy FAQ')
                                ->content(Text::make('SERIXA keeps forms SSR-first; JS only enhances overlays and disclosure widgets.')),
                        ),
                    SizedBox::height(3),
                    Button::make('Save changes')->primary()->large()->type('submit'),
                ])->gap(2)->alignStart(),
            );

        $content = PageSection::make(
            'Settings',
            'Workspace preferences with Tabs and Accordion.',
            [],
            $form,
        );

        return DocumentFactory::page(
            'Settings',
            Shell::app('settings', $content),
        );
    }
}
