<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;
use SerixaDemo\Shell;

final class SettingsPage
{
    public static function document(): Document
    {
        $content = Column::make([
            Text::make('Settings')->as('h1')->size('2xl')->weight('bold'),
            Text::make('Workspace preferences.')->tone('muted'),
            SizedBox::height(2),
            Card::make(
                Text::make('General')->as('h2')->size('lg')->weight('semibold'),
                // Missing: Input for workspace name, timezone, etc.
                Missing::widget('Input', 'Workspace name'),
                Missing::widget('Select', 'Default timezone'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Appearance')->as('h2')->size('lg')->weight('semibold'),
                // Missing: Switch / ThemeToggle (WidgetContext has colorMode, no UI)
                Missing::widget('Switch', 'Theme switch (light / dark)'),
                Text::make('Runtime exposes colorMode on WidgetContext, but there is no Switch UI.')
                    ->size('sm')
                    ->tone('muted'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Notifications')->as('h2')->size('lg')->weight('semibold'),
                Missing::widget('Checkbox', 'Email digests'),
                Missing::widget('Checkbox', 'Product updates'),
                Missing::widget('Switch', 'Push notifications'),
            )->padding('md'),
            SizedBox::height(3),
            Button::make('Save changes')->primary()->large(),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'Settings',
            Shell::app('settings', $content),
        );
    }
}
