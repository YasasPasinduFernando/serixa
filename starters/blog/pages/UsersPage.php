<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\Components\UserChip;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

/**
 * Everyone with access to the blog: name, role, status and activity.
 */
final class UsersPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $rows = [];

        foreach (Fixtures::users() as $user) {
            $rows[] = [
                UserChip::make($user['name'])->subtitle($user['email']),
                $user['role'],
                StatusBadge::make($user['statusLabel'])->status($user['status']),
                (string) $user['posts'],
                $user['joined'],
                RowActions::make()->link('Edit', '#')->link('Delete', '#'),
            ];
        }

        $table = DataTable::make()
            ->headers(['User', 'Role', 'Status', 'Posts', 'Joined', ''])
            ->rows($rows)
            ->hover();

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search users by name or email…'),
            TableFilters::make(
                Select::make()->name('role')->placeholder('All roles')->options([
                    'admin' => 'Admin',
                    'editor' => 'Editor',
                    'author' => 'Author',
                    'contributor' => 'Contributor',
                    'subscriber' => 'Subscriber',
                ]),
                ColumnVisibility::make()
                    ->column('User')
                    ->column('Role')
                    ->column('Status')
                    ->column('Posts')
                    ->column('Joined', false),
            ),
            $table,
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Users')->current(),
            ))
            ->title('Users')
            ->description('Everyone with access to this blog.')
            ->actions(ToolbarAction::make('Invite user')->icon('user-plus')->primary()->href('#'));

        return BlogLayout::wrap('users', $page);
    }
}
