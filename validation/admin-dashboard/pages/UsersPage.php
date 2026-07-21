<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Badge;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\Pagination;
use Serixa\Component\SearchField;
use Serixa\Component\SizedBox;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;

final class UsersPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()
                ->title('Users')
                ->subtitle('Directory with search, roles, and pagination.'),
            SizedBox::height(2),
            Toolbar::make()
                ->leading(
                    SearchField::make()
                        ->name('q')
                        ->placeholder('Search users by name or email'),
                )
                ->trailing(
                    Link::make('Invite')->href('/?page=users&invite=1'),
                ),
            SizedBox::height(3),
            DataTable::make()
                ->caption('Users')
                ->headers(['Name', 'Email', 'Role', 'Actions'])
                ->rows([
                    [
                        'Ada Lovelace',
                        'ada@example.com',
                        Badge::make('Admin')->color('primary'),
                        Link::make('Edit')->href('/?page=users&edit=1'),
                    ],
                    [
                        'Alan Turing',
                        'alan@example.com',
                        Badge::make('Editor')->color('success'),
                        Link::make('Edit')->href('/?page=users&edit=2'),
                    ],
                    [
                        'Grace Hopper',
                        'grace@example.com',
                        Badge::make('Viewer')->color('neutral'),
                        Link::make('Edit')->href('/?page=users&edit=3'),
                    ],
                ])
                ->striped()
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->title('No users found')
                        ->description('Try another search.'),
                ),
            SizedBox::height(3),
            Pagination::make()
                ->currentPage(1)
                ->totalPages(5)
                ->urlForPage(static fn (int $page): string => '/?page=users&p=' . $page),
        ])->gap(2)->alignStart();

        return Document::make()
            ->title('Users · Admin')
            ->body(AdminLayout::shell('users', $content));
    }
}
