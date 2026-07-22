<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\BulkActions;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

final class RolesPage
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

        foreach (Fixtures::roles() as $role) {
            $rows[] = [
                $role['name'],
                $role['slug'],
                (string) $role['users'],
                $role['description'],
                RowActions::make()->link('Edit', '#')->link('Duplicate', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search roles…'),
            TableFilters::make(
                ColumnVisibility::make()
                    ->column('Role')
                    ->column('Slug')
                    ->column('Users')
                    ->column('Description'),
            ),
            BulkActions::make(
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('1 role selected'),
            DataTable::make()
                ->headers(['Role', 'Slug', 'Users', 'Description', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('users')
                        ->title('No roles defined')
                        ->description('Create a role to start assigning CRM permissions.')
                        ->action(ToolbarAction::make('New role')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Roles')->current(),
            ))
            ->title('Roles')
            ->description('Named permission bundles for CRM teammates.')
            ->actions(ToolbarAction::make('New role')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('roles', $page);
    }
}
