<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Charts\Component\DonutChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

final class ReportsPage
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

        foreach (Fixtures::reports() as $report) {
            $rows[] = [
                $report['title'],
                $report['type'],
                $report['owner'],
                $report['updated'],
                Presenter::reportStatusBadge($report['status']),
                RowActions::make()->link('Open', '#')->link('Download', '#'),
            ];
        }

        $sourceCounts = [];
        foreach (Fixtures::leads() as $lead) {
            $source = $lead['source'];
            $sourceCounts[$source] = ($sourceCounts[$source] ?? 0) + 1;
        }

        $page = Page::make(
            InfoCard::make(
                DonutChart::make()
                    ->title('Lead sources')
                    ->labels(array_keys($sourceCounts))
                    ->dataset(
                        Dataset::make()->addSeries(
                            Series::make('Leads')->values(array_values($sourceCounts)),
                        ),
                    )
                    ->summary('Lead count by source'),
            )->title('Lead source mix'),
            SearchToolbar::make()
                ->placeholder('Search reports…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('type')->placeholder('All types')->options([
                    'Pipeline' => 'Pipeline',
                    'Leads' => 'Leads',
                    'Activity' => 'Activity',
                    'Finance' => 'Finance',
                ]),
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'ready' => 'Ready',
                    'processing' => 'Processing',
                    'failed' => 'Failed',
                ]),
            ),
            DataTable::make()
                ->headers(['Report', 'Type', 'Owner', 'Updated', 'Status', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No reports yet')
                        ->description('Generated reports will show up here.')
                        ->action(ToolbarAction::make('New report')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Reports')->current(),
            ))
            ->title('Reports')
            ->description('Saved CRM snapshots and scheduled exports.')
            ->actions(ToolbarAction::make('New report')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('reports', $page);
    }
}
