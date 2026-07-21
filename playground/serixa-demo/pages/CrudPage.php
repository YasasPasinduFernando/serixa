<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\Modal;
use Serixa\Component\Pagination;
use Serixa\Component\Progress;
use Serixa\Component\Row;
use Serixa\Component\SearchField;
use Serixa\Component\Select;
use Serixa\Component\SizedBox;
use Serixa\Component\Spinner;
use Serixa\Component\Text;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;
use SerixaDemo\Components\PageSection;
use SerixaDemo\Components\StatGrid;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Shell;

final class CrudPage
{
    public static function document(): Document
    {
        $toolbar = Toolbar::make()
            ->leading(
                Column::make([
                    SearchField::make()
                        ->name('q')
                        ->placeholder('Search users by name or email')
                        ->onInput('search'),
                    Select::make()
                        ->name('role')
                        ->placeholder('Filter by role')
                        ->options([
                            'admin' => 'Admin',
                            'editor' => 'Editor',
                            'viewer' => 'Viewer',
                        ])
                        ->onChange('filterRole'),
                ])->gap(2),
            )
            ->trailing(
                Row::make([
                    Dropdown::make()
                        ->trigger(Button::make('Bulk')->secondary())
                        ->items([
                            DropdownItem::make('Export CSV'),
                            DropdownItem::make('Archive'),
                        ]),
                    Button::make('Create')
                        ->primary()
                        ->attribute('data-serixa-open-modal', 'create-user-modal'),
                ])->gap(2)->alignCenter(),
            );

        $table = DataTable::make()
            ->caption('User directory')
            ->headers(['Name', 'Email', 'Role', 'Actions'])
            ->rows([
                [
                    'Ada Lovelace',
                    'ada@example.com',
                    Text::make('Admin')->size('sm')->weight('semibold'),
                    Link::make('Edit')->href('/?page=crud&edit=1'),
                ],
                [
                    'Alan Turing',
                    'alan@example.com',
                    Text::make('Editor')->size('sm')->tone('muted'),
                    Link::make('Edit')->href('/?page=crud&edit=2'),
                ],
                [
                    'Grace Hopper',
                    'grace@example.com',
                    Text::make('Viewer')->size('sm')->tone('muted'),
                    Link::make('Edit')->href('/?page=crud&edit=3'),
                ],
            ])
            ->striped()
            ->hover()
            ->emptyState(
                EmptyState::make()
                    ->title('No users found')
                    ->description('Try another search or create a user.')
                    ->action(Button::make('Create')->primary()),
            );

        $modal = Modal::make()
            ->id('create-user-modal')
            ->title('Create user')
            ->body(Text::make('SSR modal markup. ModalModule handles Escape, backdrop, and focus trap.'))
            ->footer(
                Button::make('Cancel')->secondary()->attribute('data-serixa-close', 'true'),
                Button::make('Save')->primary(),
            )
            ->open(false);

        $content = PageSection::make(
            'Users',
            'CRUD with Modal, Dropdown, Spinner, and Progress.',
            [Button::make('Import')->secondary()],
            StatGrid::make(
                ['title' => 'Total users', 'value' => '3', 'color' => 'primary'],
                ['title' => 'Admins', 'value' => '1', 'color' => 'success'],
                ['title' => 'Pending invites', 'value' => '0', 'subtitle' => 'Last 30 days'],
            ),
            SizedBox::height(2),
            $toolbar,
            SizedBox::height(2),
            Row::make([
                Spinner::make()->size('sm')->label('Syncing'),
                Progress::make()->value(65)->max(100)->label('Import progress')->size('sm'),
            ])->gap(4)->alignCenter(),
            SizedBox::height(3),
            $table,
            SizedBox::height(3),
            Pagination::make()
                ->currentPage(1)
                ->totalPages(4)
                ->urlForPage(static fn (int $page): string => '/?page=crud&p=' . $page),
            $modal,
        );

        return DocumentFactory::page(
            'CRUD',
            Shell::app('crud', $content),
        );
    }
}
