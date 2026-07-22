<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

final class PipelinePage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $stages = Fixtures::pipelineStages();
        $stageCards = [];

        foreach ($stages as $stage) {
            $stageCards[] = InfoCard::make(
                MetricCard::make()
                    ->title($stage['stage'])
                    ->value((string) $stage['count'] . ' deals')
                    ->trend('$' . number_format($stage['value']), 'neutral')
                    ->icon('chart-bar')
                    ->color('primary'),
            )->title($stage['stage']);
        }

        $openDeals = array_values(array_filter(
            Fixtures::opportunities(),
            static fn (array $deal): bool => $deal['stage'] !== 'Closed Won' && $deal['stage'] !== 'Closed Lost',
        ));

        $groupedRows = [];
        foreach ($openDeals as $deal) {
            $groupedRows[] = [
                $deal['name'],
                $deal['company'],
                Presenter::stageBadge($deal['stage']),
                $deal['amount'],
                $deal['owner'],
                $deal['closeDate'],
            ];
        }

        $page = Page::make(...array_merge($stageCards, [
            InfoCard::make(
                BarChart::make()
                    ->title('Stage value')
                    ->labels(array_column($stages, 'stage'))
                    ->dataset(
                        Dataset::make()->addSeries(
                            Series::make('Value')->values(array_column($stages, 'value')),
                        ),
                    )
                    ->summary('Pipeline value by stage'),
            )->title('Pipeline chart'),
            InfoCard::make(
                DataTable::make()
                    ->headers(['Opportunity', 'Company', 'Stage', 'Amount', 'Owner', 'Close date'])
                    ->rows($groupedRows)
                    ->hover(),
            )->title('Open opportunities'),
        ]))
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Pipeline')->current(),
            ))
            ->title('Pipeline')
            ->description('Deal stages, counts, and open opportunities.')
            ->actions(ToolbarAction::make('View opportunities')->icon('chart-bar')->href('?page=opportunities'));

        return PanelLayout::wrap('pipeline', $page);
    }
}
