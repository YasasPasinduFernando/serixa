<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\Modal;
use Serixa\Component\PageHeader;
use Serixa\Component\Progress;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class CrudPage
{
    public static function document(): Document
    {
        $modal = Modal::make()
            ->id('create-record-modal')
            ->title('Create record')
            ->body(Text::make('SSR modal markup. ClientRuntime ModalModule handles Escape, backdrop, and focus trap.'))
            ->footer(
                Button::make('Cancel')->secondary()->attribute('data-serixa-close', 'true'),
                Button::make('Save')->primary(),
            )
            ->open(false);

        $content = Column::make([
            PageHeader::make()
                ->title('CRUD')
                ->subtitle('Table operations with modal create and bulk actions.')
                ->actions(
                    Dropdown::make()
                        ->trigger(Button::make('Bulk')->secondary())
                        ->items([
                            DropdownItem::make('Export CSV'),
                            DropdownItem::make('Archive selected'),
                            DropdownItem::make('Delete selected'),
                        ]),
                    Button::make('Create')
                        ->primary()
                        ->attribute('data-serixa-open-modal', 'create-record-modal'),
                ),
            SizedBox::height(2),
            Progress::make()->value(45)->max(100)->label('Import progress')->size('sm'),
            SizedBox::height(3),
            DataTable::make()
                ->caption('Records')
                ->headers(['ID', 'Title', 'Status', 'Actions'])
                ->rows([
                    [
                        '#1042',
                        'Welcome email',
                        Text::make('Published')->size('sm')->weight('semibold'),
                        Link::make('Edit')->href('/?page=crud&edit=1042'),
                    ],
                    [
                        '#1043',
                        'Onboarding checklist',
                        Text::make('Draft')->size('sm')->tone('muted'),
                        Link::make('Edit')->href('/?page=crud&edit=1043'),
                    ],
                    [
                        '#1044',
                        'API changelog',
                        Text::make('Published')->size('sm')->weight('semibold'),
                        Link::make('Edit')->href('/?page=crud&edit=1044'),
                    ],
                ])
                ->striped()
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->title('No records')
                        ->description('Create a record to get started.')
                        ->action(
                            Button::make('Create')
                                ->primary()
                                ->attribute('data-serixa-open-modal', 'create-record-modal'),
                        ),
                ),
            $modal,
        ])->gap(2)->alignStart();

        return Document::make()
            ->title('CRUD · Admin')
            ->body(AdminLayout::shell('crud', $content));
    }
}
