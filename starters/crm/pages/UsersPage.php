<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Components\UserChip;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\BulkActions;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\ImportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

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
                $user['team'],
                Presenter::userStatusBadge($user['status']),
                $user['lastActive'],
                RowActions::make()->link('Edit', '#')->link('Deactivate', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search users by name or email…')
                ->actions(ImportButton::make(), ExportButton::make()),
            TableFilters::make(
                Select::make()->name('role')->placeholder('All roles')->options([
                    'owner' => 'Owner',
                    'admin' => 'Admin',
                    'manager' => 'Manager',
                    'rep' => 'Rep',
                    'viewer' => 'Viewer',
                ]),
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'active' => 'Active',
                    'invited' => 'Invited',
                    'suspended' => 'Suspended',
                ]),
                ColumnVisibility::make()
                    ->column('User')
                    ->column('Role')
                    ->column('Team')
                    ->column('Status')
                    ->column('Last active'),
            ),
            BulkActions::make(
                ToolbarAction::make('Deactivate')->icon('lock-closed'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('2 users selected'),
            DataTable::make()
                ->headers(['User', 'Role', 'Team', 'Status', 'Last active', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('users')
                        ->title('No users yet')
                        ->description('Invite your first teammate to populate this directory.')
                        ->action(ToolbarAction::make('Invite user')->icon('user-plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Users')->current(),
            ))
            ->title('Users')
            ->description('Everyone with access to this CRM workspace.')
            ->actions(ToolbarAction::make('Invite user')->icon('user-plus')->primary()->href('#'));

        return PanelLayout::wrap('users', $page);
    }
}
