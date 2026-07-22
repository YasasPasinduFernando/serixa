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

final class LeadsPage
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

        foreach (Fixtures::leads() as $lead) {
            $rows[] = [
                UserChip::make($lead['name'])->subtitle($lead['company']),
                $lead['company'],
                $lead['source'],
                Presenter::leadStatusBadge($lead['status']),
                (string) $lead['score'],
                $lead['owner'],
                $lead['value'],
                $lead['updated'],
                RowActions::make()->link('View', '?page=lead&id=' . $lead['id']),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search leads by name or company…')
                ->actions(ImportButton::make(), ExportButton::make()),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'qualified' => 'Qualified',
                    'unqualified' => 'Unqualified',
                ]),
                Select::make()->name('source')->placeholder('All sources')->options([
                    'Inbound' => 'Inbound',
                    'Outbound' => 'Outbound',
                    'Partner' => 'Partner',
                    'Event' => 'Event',
                ]),
                ColumnVisibility::make()
                    ->column('Lead')
                    ->column('Company')
                    ->column('Source')
                    ->column('Status')
                    ->column('Score')
                    ->column('Owner')
                    ->column('Value')
                    ->column('Updated', false),
            ),
            BulkActions::make(
                ToolbarAction::make('Qualify')->icon('check'),
                ToolbarAction::make('Assign')->icon('users'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('2 leads selected'),
            DataTable::make()
                ->headers(['Lead', 'Company', 'Source', 'Status', 'Score', 'Owner', 'Value', 'Updated', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No leads yet')
                        ->description('Capture your first lead to start filling the pipeline.')
                        ->action(ToolbarAction::make('New lead')->icon('user-plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Leads')->current(),
            ))
            ->title('Leads')
            ->description('Inbound and outbound prospects waiting to be qualified.')
            ->actions(ToolbarAction::make('New lead')->icon('user-plus')->primary()->href('#'));

        return PanelLayout::wrap('leads', $page);
    }
}
