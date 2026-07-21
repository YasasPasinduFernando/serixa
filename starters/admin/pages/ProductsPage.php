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
use Serixa\Admin\Tables\ImportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

final class ProductsPage
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

        foreach (Fixtures::products() as $product) {
            $rows[] = [
                $product['sku'],
                $product['name'],
                $product['category'],
                $product['price'],
                (string) $product['stock'],
                Presenter::productStatusBadge($product['status']),
                $product['updatedAt'],
                RowActions::make()->link('Edit', '#')->link('Archive', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search products by name or SKU…')
                ->actions(ImportButton::make(), ExportButton::make()),
            TableFilters::make(
                Select::make()->name('category')->placeholder('All categories')->options([
                    'subscriptions' => 'Subscriptions',
                    'add-ons' => 'Add-ons',
                    'assets' => 'Assets',
                    'hardware' => 'Hardware',
                    'services' => 'Services',
                ]),
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'active' => 'Active',
                    'draft' => 'Draft',
                    'archived' => 'Archived',
                ]),
                ColumnVisibility::make()
                    ->column('SKU')
                    ->column('Name')
                    ->column('Category')
                    ->column('Price')
                    ->column('Stock')
                    ->column('Status')
                    ->column('Updated', false),
            ),
            BulkActions::make(
                ToolbarAction::make('Archive')->icon('eye'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('2 products selected'),
            DataTable::make()
                ->headers(['SKU', 'Name', 'Category', 'Price', 'Stock', 'Status', 'Updated', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('cube')
                        ->title('No products')
                        ->description('Add a product to start selling.')
                        ->action(ToolbarAction::make('New product')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Products')->current(),
            ))
            ->title('Products')
            ->description('Catalog items available to customers.')
            ->actions(ToolbarAction::make('New product')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('products', $page);
    }
}
