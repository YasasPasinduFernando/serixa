<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
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
use Serixa\Document\Document;

final class ContactsPage
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

        foreach (Fixtures::contacts() as $contact) {
            $rows[] = [
                UserChip::make($contact['name'])->subtitle($contact['email']),
                $contact['title'],
                $contact['company'],
                $contact['phone'],
                $contact['owner'],
                RowActions::make()->link('View', '#')->link('Email', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search contacts…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                ColumnVisibility::make()
                    ->column('Contact')
                    ->column('Title')
                    ->column('Company')
                    ->column('Phone')
                    ->column('Owner'),
            ),
            BulkActions::make(
                ToolbarAction::make('Export')->icon('arrow-path'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('2 contacts selected'),
            DataTable::make()
                ->headers(['Contact', 'Title', 'Company', 'Phone', 'Owner', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('users')
                        ->title('No contacts')
                        ->description('People at your accounts will appear here.')
                        ->action(ToolbarAction::make('New contact')->icon('user-plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Contacts')->current(),
            ))
            ->title('Contacts')
            ->description('People linked to companies and opportunities.')
            ->actions(ToolbarAction::make('New contact')->icon('user-plus')->primary()->href('#'));

        return PanelLayout::wrap('contacts', $page);
    }
}
