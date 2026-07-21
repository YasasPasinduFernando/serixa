<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\Pagination;
use Serixa\Component\SearchField;
use Serixa\Component\Select;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;
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
            ->trailing(Button::make('Create')->primary()->onClick('createUser'));

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

        $content = Column::make([
            PageHeader::make()
                ->title('Users')
                ->subtitle('CRUD table demo using DataTable, Toolbar, and Pagination.')
                ->actions(Button::make('Import')->secondary()),
            SizedBox::height(2),
            $toolbar,
            SizedBox::height(3),
            $table,
            SizedBox::height(3),
            Pagination::make()
                ->currentPage(1)
                ->totalPages(4)
                ->urlForPage(static fn (int $page): string => '/?page=crud&p=' . $page),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'CRUD',
            Shell::app('crud', $content),
        );
    }
}
