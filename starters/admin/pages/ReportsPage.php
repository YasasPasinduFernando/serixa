<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Tables\TableToolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

/**
 * Reports catalog with chart placeholders only — no charts package.
 */
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
                $report['name'],
                $report['type'],
                $report['generated'],
                Presenter::reportStatusBadge($report['status']),
                RowActions::make()->link('Download', '#')->link('Delete', '#'),
            ];
        }

        $ready = count(array_filter(Fixtures::reports(), static fn (array $r): bool => $r['status'] === 'ready'));
        $processing = count(array_filter(Fixtures::reports(), static fn (array $r): bool => $r['status'] === 'processing'));
        $failed = count(array_filter(Fixtures::reports(), static fn (array $r): bool => $r['status'] === 'failed'));

        $page = Page::make(
            MetricGrid::make(
                MetricCard::make()->title('Ready')->value((string) $ready)->icon('document-text')->color('primary'),
                MetricCard::make()->title('Processing')->value((string) $processing)->icon('arrow-path')->color('warning'),
                MetricCard::make()->title('Failed')->value((string) $failed)->icon('exclamation-triangle')->color('danger'),
            ),
            InfoCard::make(
                EmptyState::make()
                    ->icon('chart-bar')
                    ->title('Revenue chart placeholder')
                    ->description('Wire a charts package or embed your analytics iframe here. This starter intentionally ships UI only.'),
            )->title('Revenue over time'),
            InfoCard::make(
                EmptyState::make()
                    ->icon('chart-pie')
                    ->title('Breakdown chart placeholder')
                    ->description('Replace this card body with your preferred visualization once you add a charts dependency.'),
            )->title('Orders by status'),
            TableToolbar::make()->actions(ExportButton::make('Export all')),
            TableFilters::make(
                Select::make()->name('type')->placeholder('All types')->options([
                    'finance' => 'Finance',
                    'growth' => 'Growth',
                    'operations' => 'Operations',
                    'compliance' => 'Compliance',
                ]),
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'ready' => 'Ready',
                    'processing' => 'Processing',
                    'failed' => 'Failed',
                ]),
            ),
            DataTable::make()
                ->headers(['Report', 'Type', 'Generated', 'Status', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('document-text')
                        ->title('No reports yet')
                        ->description('Generated reports will show up here.')
                        ->action(ToolbarAction::make('Generate report')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Reports')->current(),
            ))
            ->title('Reports')
            ->description('Generated and scheduled reports across your workspace.')
            ->actions(ToolbarAction::make('New report')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('reports', $page);
    }
}
