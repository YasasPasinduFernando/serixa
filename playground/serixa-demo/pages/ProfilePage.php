<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Avatar;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Link;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Component\Textarea;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
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
                Avatar::make()->initials('AL')->size('xl')->circle(),
                Text::make('Ada Lovelace')->as('h2')->size('lg')->weight('semibold'),
                Text::make('ada@example.com')->tone('muted')->size('sm'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Personal details')->as('h2')->size('lg')->weight('semibold'),
                Label::make('Full name')->for('full_name'),
                Input::make()->id('full_name')->name('full_name')->value('Ada Lovelace'),
                Label::make('Job title')->for('job_title'),
                Input::make()->id('job_title')->name('job_title')->value('Mathematician'),
                Label::make('Location')->for('location'),
                Input::make()->id('location')->name('location')->value('London'),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Bio')->as('h2')->size('lg')->weight('semibold'),
                Label::make('Biography')->for('bio'),
                Textarea::make('Pioneer of computing.')->id('bio')->name('bio')->rows(4),
            )->padding('md'),
            SizedBox::height(3),
            Card::make(
                Text::make('Social links')->as('h2')->size('lg')->weight('semibold'),
                Label::make('Website')->for('website'),
                Input::make()->id('website')->name('website')->type('url')->value('https://example.com/ada'),
                Link::make('https://example.com/ada')
                    ->href('https://example.com/ada')
                    ->target('_blank'),
            )->padding('md'),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'Profile',
            Shell::app('profile', $content),
        );
    }
}
