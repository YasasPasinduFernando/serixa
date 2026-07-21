<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Avatar;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Component\Textarea;
use Serixa\Component\Tooltip;
use Serixa\Document\Document;

/**
 * User profile with avatar, badge, tooltip, and edit form.
 */
final class ProfilePage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Profile')
                ->subtitle('Your CRM account'),
            Row::make([
                Tooltip::make()
                    ->text('Signed-in sales lead')
                    ->child(Avatar::make()->initials('JL')->size('xl')->circle()),
                Column::make([
                    Text::make('Jordan Lee')->as('h2')->size('xl')->weight('bold'),
                    Text::make('jordan@serixa.example')->tone('muted'),
                    Badge::make('Sales Lead')->color('primary'),
                ])->gap(1)->alignStart(),
            ])->gap(4)->alignCenter(),
            Form::make()
                ->method('POST')
                ->action('/?page=profile')
                ->child(
                    Column::make([
                        Label::make('Display name')->for('display_name'),
                        Input::make()
                            ->id('display_name')
                            ->name('display_name')
                            ->placeholder('Jordan Lee'),
                        Label::make('Email')->for('email'),
                        Input::make()
                            ->id('email')
                            ->name('email')
                            ->type('email')
                            ->placeholder('jordan@serixa.example'),
                        Label::make('Bio')->for('bio'),
                        Textarea::make()
                            ->id('bio')
                            ->name('bio')
                            ->rows(3)
                            ->placeholder('Short intro for teammates'),
                        Button::make('Save profile')->primary()->type('submit'),
                    ])->gap(3),
                ),
        ])->gap(4);

        return Document::make()
            ->title('Profile · Serixa CRM')
            ->description('Profile')
            ->language('en')
            ->body(CrmLayout::shell('profile', $body));
    }
}
