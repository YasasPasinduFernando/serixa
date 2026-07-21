<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Avatar;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Component\Tooltip;
use Serixa\Document\Document;

final class ProfilePage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()
                ->title('Profile')
                ->subtitle('Signed-in admin identity.')
                ->actions(Button::make('Edit')->secondary()),
            SizedBox::height(3),
            Card::make(
                Row::make([
                    Tooltip::make()
                        ->text('Verified administrator')
                        ->child(Avatar::make()->initials('AL')->size('xl')->circle()),
                    Column::make([
                        Row::make([
                            Text::make('Ada Lovelace')->as('h2')->size('lg')->weight('semibold'),
                            Badge::make('Admin')->color('primary'),
                        ])->gap(2)->alignCenter(),
                        Text::make('ada@example.com')->tone('muted')->size('sm'),
                    ])->gap(1),
                ])->gap(4)->alignCenter(),
            )->padding('md'),
        ])->gap(2)->alignStart();

        return Document::make()
            ->title('Profile · Admin')
            ->body(AdminLayout::shell('profile', $content));
    }
}
