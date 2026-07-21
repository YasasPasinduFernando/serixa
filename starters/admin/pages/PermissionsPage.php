<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

final class PermissionsPage
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

        foreach (Fixtures::permissions() as $permission) {
            $rows[] = [
                $permission['name'],
                $permission['group'],
                $permission['description'],
                RowActions::make()->link('View roles', '?page=roles'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search permissions…'),
            TableFilters::make(
                Select::make()->name('group')->placeholder('All groups')->options([
                    'people' => 'People',
                    'commerce' => 'Commerce',
                    'finance' => 'Finance',
                    'insights' => 'Insights',
                    'security' => 'Security',
                    'system' => 'System',
                ]),
                ColumnVisibility::make()
                    ->column('Permission')
                    ->column('Group')
                    ->column('Description'),
            ),
            DataTable::make()
                ->headers(['Permission', 'Group', 'Description', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('key')
                        ->title('No permissions')
                        ->description('Permissions appear here once your auth layer defines them.'),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Permissions')->current(),
            ))
            ->title('Permissions')
            ->description('Fine-grained capabilities that roles compose together.')
            ->actions(ToolbarAction::make('Export')->icon('arrow-path')->href('#'));

        return PanelLayout::wrap('permissions', $page);
    }
}
