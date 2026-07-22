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

final class OpportunitiesPage
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

        foreach (Fixtures::opportunities() as $deal) {
            $rows[] = [
                $deal['name'],
                $deal['company'],
                Presenter::stageBadge($deal['stage']),
                $deal['amount'],
                $deal['closeDate'],
                $deal['owner'],
                $deal['probability'] . '%',
                RowActions::make()->link('View', '#')->link('Edit', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search opportunities…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('stage')->placeholder('All stages')->options([
                    'Qualify' => 'Qualify',
                    'Discovery' => 'Discovery',
                    'Proposal' => 'Proposal',
                    'Negotiation' => 'Negotiation',
                    'Closed Won' => 'Closed Won',
                ]),
                ColumnVisibility::make()
                    ->column('Opportunity')
                    ->column('Company')
                    ->column('Stage')
                    ->column('Amount')
                    ->column('Close date')
                    ->column('Owner')
                    ->column('Probability'),
            ),
            BulkActions::make(
                ToolbarAction::make('Move stage')->icon('arrow-path'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('1 opportunity selected'),
            DataTable::make()
                ->headers(['Opportunity', 'Company', 'Stage', 'Amount', 'Close date', 'Owner', 'Probability', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No opportunities')
                        ->description('Convert a qualified lead into a deal to populate this list.')
                        ->action(ToolbarAction::make('New opportunity')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Opportunities')->current(),
            ))
            ->title('Opportunities')
            ->description('Open and closed deals across your sales pipeline.')
            ->actions(ToolbarAction::make('New opportunity')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('opportunities', $page);
    }
}
