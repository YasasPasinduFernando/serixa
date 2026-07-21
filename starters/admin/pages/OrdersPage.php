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

final class OrdersPage
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

        foreach (Fixtures::orders() as $order) {
            $rows[] = [
                $order['number'],
                $order['customer'],
                $order['total'],
                (string) $order['items'],
                Presenter::orderStatusBadge($order['status']),
                $order['placedAt'],
                RowActions::make()->link('View', '#')->link('Refund', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search orders by number or customer…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'paid' => 'Paid',
                    'processing' => 'Processing',
                    'fulfilled' => 'Fulfilled',
                    'pending' => 'Pending',
                    'cancelled' => 'Cancelled',
                ]),
                ColumnVisibility::make()
                    ->column('Order')
                    ->column('Customer')
                    ->column('Total')
                    ->column('Items')
                    ->column('Status')
                    ->column('Placed'),
            ),
            BulkActions::make(
                ToolbarAction::make('Mark fulfilled')->icon('check'),
                ToolbarAction::make('Cancel')->icon('x-mark')->danger(),
            )->summary('2 orders selected'),
            DataTable::make()
                ->headers(['Order', 'Customer', 'Total', 'Items', 'Status', 'Placed', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('shopping-cart')
                        ->title('No orders yet')
                        ->description('Incoming orders will appear in this queue.'),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Orders')->current(),
            ))
            ->title('Orders')
            ->description('Purchase orders across every customer.')
            ->actions(ToolbarAction::make('New order')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('orders', $page);
    }
}
