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
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

final class CustomersPage
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

        foreach (Fixtures::customers() as $customer) {
            $rows[] = [
                UserChip::make($customer['name'])->subtitle($customer['email']),
                $customer['company'],
                (string) $customer['orders'],
                $customer['lifetime'],
                Presenter::userStatusBadge($customer['status']),
                $customer['since'],
                RowActions::make()->link('View', '#')->link('Message', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search customers by name, email, or company…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'active' => 'Active',
                    'invited' => 'Invited',
                    'suspended' => 'Suspended',
                ]),
                ColumnVisibility::make()
                    ->column('Customer')
                    ->column('Company')
                    ->column('Orders')
                    ->column('Lifetime value')
                    ->column('Status')
                    ->column('Customer since', false),
            ),
            BulkActions::make(
                ToolbarAction::make('Export')->icon('arrow-path'),
                ToolbarAction::make('Suspend')->icon('lock-closed')->danger(),
            )->summary('2 customers selected'),
            DataTable::make()
                ->headers(['Customer', 'Company', 'Orders', 'Lifetime value', 'Status', 'Customer since', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('user-plus')
                        ->title('No customers')
                        ->description('Customer accounts will land here once orders arrive.'),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Customers')->current(),
            ))
            ->title('Customers')
            ->description('Accounts that buy from your workspace.')
            ->actions(ToolbarAction::make('Add customer')->icon('user-plus')->primary()->href('#'));

        return PanelLayout::wrap('customers', $page);
    }
}
