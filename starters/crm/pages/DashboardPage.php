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
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Toolbar\Toolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Calendar\Component\MonthView;
use Serixa\Calendar\Event\Event;
use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Component\LineChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

/**
 * CRM overview: KPIs, revenue trend, pipeline, calendar, and recent activity.
 */
final class DashboardPage
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

        $activityRows = [];
        foreach (Fixtures::activityFeed() as $item) {
            $activityRows[] = [
                $item['actor'],
                $item['summary'],
                $item['at'],
            ];
        }

        $leadRows = [];
        foreach (array_slice(Fixtures::leads(), 0, 5) as $lead) {
            $leadRows[] = [
                $lead['name'],
                $lead['company'],
                Presenter::leadStatusBadge($lead['status']),
                $lead['value'],
                RowActions::make()->link('View', '?page=lead&id=' . $lead['id']),
            ];
        }

        $calendarEvents = [];
        foreach (Fixtures::calendarEvents() as $event) {
            $calendarEvents[] = Event::make($event['title'])
                ->start($event['start'])
                ->end($event['end'])
                ->color($event['color']);
        }

        $page = Page::make(
            MetricGrid::make(
                MetricCard::make()->title('Revenue')->value($kpis['revenue'])->trend('+12%', 'up')->icon('banknotes')->color('success'),
                MetricCard::make()->title('Open deals')->value((string) $kpis['openDeals'])->trend('+2', 'up')->icon('chart-bar')->color('primary'),
                MetricCard::make()->title('Active leads')->value((string) $kpis['activeLeads'])->trend('+5', 'up')->icon('users')->color('warning'),
                MetricCard::make()->title('Conversion')->value($kpis['conversion'])->trend('+1.2%', 'up')->icon('arrow-path')->color('danger'),
            ),
            Toolbar::make(
                ToolbarAction::make('New lead')->icon('user-plus')->primary()->href('?page=leads'),
                ToolbarAction::make('Pipeline')->icon('chart-bar')->href('?page=pipeline'),
                ToolbarAction::make('Calendar')->icon('calendar')->href('?page=calendar'),
                ToolbarAction::make('Reports')->icon('document-text')->href('?page=reports'),
            ),
            InfoCard::make(
                LineChart::make()
                    ->title('Revenue')
                    ->labels(array_column($revenue, 'month'))
                    ->dataset(
                        Dataset::make()->addSeries(
                            Series::make('Revenue')->values(array_column($revenue, 'revenue')),
                        ),
                    )
                    ->smooth()
                    ->legend()
                    ->summary('Monthly CRM revenue'),
            )->title('Revenue by month'),
            InfoCard::make(
                BarChart::make()
                    ->title('Pipeline')
                    ->labels(array_column($stages, 'stage'))
                    ->dataset(
                        Dataset::make()->addSeries(
                            Series::make('Deals')->values(array_column($stages, 'count')),
                        ),
                    )
                    ->summary('Open deals by pipeline stage'),
            )->title('Pipeline stages'),
            InfoCard::make(
                MonthView::make()
                    ->focus('2026-07-01')
                    ->today('2026-07-22')
                    ->events($calendarEvents)
                    ->summary('CRM meetings this month'),
            )->title('Calendar'),
            InfoCard::make(
                DataTable::make()
                    ->headers(['Actor', 'Activity', 'When'])
                    ->rows($activityRows)
                    ->hover(),
            )->title('Recent activity'),
            InfoCard::make(
                DataTable::make()
                    ->headers(['Lead', 'Company', 'Status', 'Value', ''])
                    ->rows($leadRows)
                    ->hover(),
            )->title('Recent leads'),
        )
            ->breadcrumb(Breadcrumb::make(BreadcrumbItem::make('Dashboard')->current()))
            ->title('Dashboard')
            ->description('Sales overview for your CRM workspace.')
            ->actions(ToolbarAction::make('Refresh')->icon('arrow-path')->href('?page=dashboard'));

        return PanelLayout::wrap('dashboard', $page);
    }
}
