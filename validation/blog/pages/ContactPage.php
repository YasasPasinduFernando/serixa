<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Alert;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\Textarea;
use Serixa\Document\Document;

final class ContactPage
{
    public static function document(): Document
    {
        $form = Form::make(
            Column::make([
                FieldGroup::make(
                    Label::make('Name')->for('contact-name'),
                    Input::make()->id('contact-name')->name('name')->autocomplete('name')->required(),
                ),
                FieldGroup::make(
                    Label::make('Email')->for('contact-email'),
                    Input::make()->id('contact-email')->name('email')->type('email')->autocomplete('email')->required(),
                ),
                FieldGroup::make(
                    Label::make('Message')->for('contact-message'),
                    Textarea::make()->id('contact-message')->name('message')->rows(7)->minLength(10)->required(),
                ),
                Button::make('Send message')->type('submit')->primary(),
            ])->gap(4),
        )->method('POST')->action('/?page=contact');

        return Document::make()
            ->title('Contact · SERIXA Journal')
            ->body(SiteLayout::shell('contact', Column::make([
                PageHeader::make()->title('Contact')->subtitle('Questions, pitches, and thoughtful notes are welcome.'),
                Alert::make()->tone('info')->title('Response time')->body('We usually reply within two working days.'),
                $form,
            ])->gap(5)));
    }
}
