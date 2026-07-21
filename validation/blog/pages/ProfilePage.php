<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Avatar;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\Textarea;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class ProfilePage
{
    public static function document(): Document
    {
        $identity = Row::make([
            Avatar::make()->initials('AM')->size('xl'),
            Column::make([
                Text::make('Alex Morgan')->as('h2')->size('xl')->weight('bold'),
                Badge::make('Contributing author')->color('success'),
                Text::make('Joined March 2025')->size('sm')->tone('muted'),
            ])->gap(2)->alignStart(),
        ])->gap(4)->alignCenter();

        $form = Form::make(Column::make([
            FieldGroup::make(
                Label::make('Display name')->for('profile-name'),
                Input::make()->id('profile-name')->name('name')->value('Alex Morgan')->required(),
            ),
            FieldGroup::make(
                Label::make('Email')->for('profile-email'),
                Input::make()->id('profile-email')->name('email')->type('email')->value('alex@example.com')->required(),
            ),
            FieldGroup::make(
                Label::make('Bio')->for('profile-bio'),
                Textarea::make('Writer and software engineer exploring resilient web interfaces.')
                    ->id('profile-bio')->name('bio')->rows(5)->maxLength(280),
            ),
            Button::make('Save profile')->type('submit')->primary(),
        ])->gap(4))->method('POST')->action('/?page=profile');

        return Document::make()
            ->title('Profile · SERIXA Journal')
            ->body(SiteLayout::shell('profile', Column::make([
                PageHeader::make()->title('Your profile')->subtitle('Keep your public author details up to date.'),
                $identity,
                $form,
            ])->gap(5)));
    }
}
