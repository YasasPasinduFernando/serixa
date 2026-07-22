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

final class InvoicesPage
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

        foreach (Fixtures::invoices() as $invoice) {
            $rows[] = [
                $invoice['number'],
                $invoice['company'],
                $invoice['amount'],
                Presenter::invoiceStatusBadge($invoice['status']),
                $invoice['issued'],
                $invoice['due'],
                RowActions::make()->link('View', '#')->link('Send', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search invoices…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'paid' => 'Paid',
                    'open' => 'Open',
                    'overdue' => 'Overdue',
                    'draft' => 'Draft',
                ]),
                ColumnVisibility::make()
                    ->column('Invoice')
                    ->column('Company')
                    ->column('Amount')
                    ->column('Status')
                    ->column('Issued')
                    ->column('Due'),
            ),
            BulkActions::make(
                ToolbarAction::make('Send reminders')->icon('bell'),
                ToolbarAction::make('Void')->icon('trash')->danger(),
            )->summary('1 invoice selected'),
            DataTable::make()
                ->headers(['Invoice', 'Company', 'Amount', 'Status', 'Issued', 'Due', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No invoices')
                        ->description('Create an invoice to bill a customer.')
                        ->action(ToolbarAction::make('New invoice')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Invoices')->current(),
            ))
            ->title('Invoices')
            ->description('Billing documents sent to CRM accounts.')
            ->actions(ToolbarAction::make('New invoice')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('invoices', $page);
    }
}
