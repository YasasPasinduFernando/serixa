<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\Cards\Statistic;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\Toolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\Row;
use Serixa\Document\Document;

/**
 * Analytics overview with metric cards and chart placeholders (UI only).
 */
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
        $snapshot = Fixtures::analyticsSnapshot();
        $revenue = Fixtures::revenueSummary();

        $page = Page::make(
            MetricGrid::make(
                MetricCard::make()->title('Sessions')->value($snapshot['sessions'])->trend('+8.1%', 'up')->icon('chart-bar')->color('primary'),
                MetricCard::make()->title('Active accounts')->value($snapshot['activeAccounts'])->trend('+2.4%', 'up')->icon('users')->color('success'),
                MetricCard::make()->title('Bounce rate')->value($snapshot['bounce'])->trend('+0.6%', 'down')->icon('arrow-path')->color('warning'),
                MetricCard::make()->title('Open tickets')->value($snapshot['supportOpen'])->trend('-3', 'up')->icon('inbox')->color('danger'),
            ),
            InfoCard::make(
                Row::make([
                    Statistic::make('MRR', $revenue['mrr']),
                    Statistic::make('ARR', $revenue['arr']),
                    Statistic::make('Avg. order', $revenue['avgOrder']),
                    Statistic::make('NPS', $revenue['nps']),
                ])->gap(6),
            )->title('Funnel snapshot'),
            Toolbar::make(
                ToolbarAction::make('Export CSV')->icon('arrow-path')->href('#'),
                ToolbarAction::make('Open reports')->icon('chart-bar')->href('?page=reports'),
            ),
            InfoCard::make(
                EmptyState::make()
                    ->icon('chart-bar')
                    ->title('Traffic chart placeholder')
                    ->description('Drop in a chart library or an analytics embed. No charts package ships with this starter.'),
            )->title('Traffic (30 days)'),
            InfoCard::make(
                EmptyState::make()
                    ->icon('chart-pie')
                    ->title('Cohort chart placeholder')
                    ->description('Use this slot for retention cohorts once you connect real analytics data.'),
            )->title('Retention cohorts'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Analytics')->current(),
            ))
            ->title('Analytics')
            ->description('Product and revenue signals at a glance. Charts are placeholders only.')
            ->actions(ToolbarAction::make('Refresh')->icon('arrow-path')->href('?page=analytics'));

        return PanelLayout::wrap('analytics', $page);
    }
}
