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

final class DepartmentsPage
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

        foreach (Fixtures::departments() as $department) {
            $rows[] = [
                $department['name'],
                $department['code'],
                $department['lead'],
                (string) $department['members'],
                $department['location'],
                RowActions::make()->link('Edit', '#')->link('View members', '?page=users'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search departments…'),
            TableFilters::make(
                ColumnVisibility::make()
                    ->column('Department')
                    ->column('Code')
                    ->column('Lead')
                    ->column('Members')
                    ->column('Location'),
            ),
            BulkActions::make(
                ToolbarAction::make('Archive')->icon('trash')->danger(),
            )->summary('1 department selected'),
            DataTable::make()
                ->headers(['Department', 'Code', 'Lead', 'Members', 'Location', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('building-office')
                        ->title('No departments')
                        ->description('Create a department to organize teams.')
                        ->action(ToolbarAction::make('New department')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Departments')->current(),
            ))
            ->title('Departments')
            ->description('Organizational units and their leads.')
            ->actions(ToolbarAction::make('New department')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('departments', $page);
    }
}
