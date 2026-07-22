<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\BulkActions;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

final class CompaniesPage
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

        foreach (Fixtures::companies() as $company) {
            $rows[] = [
                $company['name'],
                $company['industry'],
                $company['city'],
                (string) $company['employees'],
                $company['owner'],
                Presenter::companyStatusBadge($company['status']),
                RowActions::make()->link('View', '#')->link('Edit', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search companies…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'customer' => 'Customer',
                    'prospect' => 'Prospect',
                    'churned' => 'Churned',
                ]),
                ColumnVisibility::make()
                    ->column('Company')
                    ->column('Industry')
                    ->column('City')
                    ->column('Employees')
                    ->column('Owner')
                    ->column('Status'),
            ),
            BulkActions::make(
                ToolbarAction::make('Export')->icon('arrow-path'),
                ToolbarAction::make('Archive')->icon('trash')->danger(),
            )->summary('1 company selected'),
            DataTable::make()
                ->headers(['Company', 'Industry', 'City', 'Employees', 'Owner', 'Status', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No companies')
                        ->description('Add accounts your team sells to.')
                        ->action(ToolbarAction::make('New company')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Companies')->current(),
            ))
            ->title('Companies')
            ->description('Accounts and organizations in your CRM.')
            ->actions(ToolbarAction::make('New company')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('companies', $page);
    }
}
