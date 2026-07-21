<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Accordion;
use Serixa\Component\AccordionItem;
use Serixa\Component\Chip;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class RolesPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()
                ->title('Roles')
                ->subtitle('Permission sets as accordion sections.'),
            SizedBox::height(2),
            Accordion::make()
                ->item(
                    AccordionItem::make()
                        ->title('Admin')
                        ->open()
                        ->content(
                            Column::make([
                                Text::make('Full access to users, roles, and settings.')->tone('muted'),
                                Row::make([
                                    Chip::make('users.manage')->color('primary'),
                                    Chip::make('roles.manage')->color('primary'),
                                    Chip::make('settings.write')->color('warning'),
                                ])->gap(2),
                            ])->gap(2),
                        ),
                )
                ->item(
                    AccordionItem::make()
                        ->title('Editor')
                        ->content(
                            Column::make([
                                Text::make('Can create and update content records.')->tone('muted'),
                                Row::make([
                                    Chip::make('content.write')->color('success'),
                                    Chip::make('content.read')->color('neutral'),
                                ])->gap(2),
                            ])->gap(2),
                        ),
                )
                ->item(
                    AccordionItem::make()
                        ->title('Viewer')
                        ->content(
                            Column::make([
                                Text::make('Read-only access across the admin.')->tone('muted'),
                                Row::make([
                                    Chip::make('content.read')->color('neutral'),
                                    Chip::make('analytics.read')->dismissible()->color('neutral'),
                                ])->gap(2),
                            ])->gap(2),
                        ),
                ),
        ])->gap(2)->alignStart();

        return Document::make()
            ->title('Roles · Admin')
            ->body(AdminLayout::shell('roles', $content));
    }
}
