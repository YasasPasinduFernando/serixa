<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;
use SerixaDemo\Shell;

final class ProfilePage
{
    public static function document(): Document
    {
        $content = Column::make([
            Row::make([
                Text::make('Profile')->as('h1')->size('2xl')->weight('bold'),
                Button::make('Edit')->secondary(),
            ])->alignCenter()->spaceBetween(),
            Text::make('Personal presence and public details.')->tone('muted'),
            SizedBox::height(3),
            Card::make(
                // Missing: Avatar
                Missing::widget('Avatar', 'Circular user photo / initials'),
                Text::make('Ada Lovelace')->as('h2')->size('lg')->weight('semibold'),
                Text::make('ada@example.com')->tone('muted')->size('sm'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Personal details')->as('h2')->size('lg')->weight('semibold'),
                Missing::widget('Input', 'Full name'),
                Missing::widget('Input', 'Job title'),
                Missing::widget('Input', 'Location'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Bio')->as('h2')->size('lg')->weight('semibold'),
                // Missing: Textarea
                Missing::widget('Textarea', 'Multi-line biography'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Social links')->as('h2')->size('lg')->weight('semibold'),
                // Missing: LinkList / IconLink
                Missing::widget('LinkField', 'Website URL'),
                Missing::widget('LinkField', 'GitHub / X / LinkedIn'),
                Text::make('https://example.com/ada')
                    ->as('a')
                    ->size('sm')
                    ->tone('primary')
                    ->attribute('href', 'https://example.com/ada')
                    ->attribute('target', '_blank'),
            )->padding('md'),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'Profile',
            Shell::app('profile', $content),
        );
    }
}
