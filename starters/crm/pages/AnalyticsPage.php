<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Charts\Component\DonutChart;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Document\Document;

final class AnalyticsPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $kpis = Fixtures::crmKpis();
        $revenue = Fixtures::revenueByMonth();
        $stages = Fixtures::pipelineStages();

        $page = Page::make(
            MetricGrid::make(
                MetricCard::make()->title('Revenue')->value($kpis['revenue'])->trend('+12%', 'up')->icon('banknotes')->color('success'),
                MetricCard::make()->title('Pipeline value')->value($kpis['pipelineValue'])->trend('+4%', 'up')->icon('chart-bar')->color('primary'),
                MetricCard::make()->title('Avg. deal')->value($kpis['avgDeal'])->trend('+1.1%', 'up')->icon('document-text')->color('warning'),
                MetricCard::make()->title('Conversion')->value($kpis['conversion'])->trend('+0.8%', 'up')->icon('arrow-path')->color('danger'),
            ),
            InfoCard::make(
                LineChart::make()
                    ->title('Revenue trend')
                    ->labels(array_column($revenue, 'month'))
                    ->dataset(
                        Dataset::make()->addSeries(
                            Series::make('Revenue')->values(array_column($revenue, 'revenue')),
                        ),
                    )
                    ->smooth()
                    ->legend()
                    ->summary('Revenue by month'),
            )->title('Revenue'),
            InfoCard::make(
                DonutChart::make()
                    ->title('Pipeline mix')
                    ->labels(array_column($stages, 'stage'))
                    ->dataset(
                        Dataset::make()->addSeries(
                            Series::make('Deals')->values(array_column($stages, 'count')),
                        ),
                    )
                    ->summary('Deal count by pipeline stage'),
            )->title('Pipeline'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Analytics')->current(),
            ))
            ->title('Analytics')
            ->description('Revenue and pipeline signals at a glance.')
            ->actions(ToolbarAction::make('Refresh')->icon('arrow-path')->href('?page=analytics'));

        return PanelLayout::wrap('analytics', $page);
    }
}
