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
use Serixa\Admin\Cards\Statistic;
use Serixa\Admin\Components\UserChip;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Toolbar\Toolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Row;
use Serixa\Document\Document;

/**
 * Operations overview: KPIs, revenue, recent activity, and quick jumps.
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
        $revenue = Fixtures::revenueSummary();
        $users = Fixtures::users();
        $orders = Fixtures::orders();

        $recentUserRows = [];
        foreach (array_slice($users, 0, 5) as $user) {
            $recentUserRows[] = [
                UserChip::make($user['name'])->subtitle($user['email']),
                $user['role'],
                Presenter::userStatusBadge($user['status']),
                $user['lastActive'],
            ];
        }

        $recentOrderRows = [];
        foreach (array_slice($orders, 0, 5) as $order) {
            $recentOrderRows[] = [
                $order['number'],
                $order['customer'],
                $order['total'],
                Presenter::orderStatusBadge($order['status']),
                $order['placedAt'],
                RowActions::make()->link('View', '?page=orders'),
            ];
        }

        $activityRows = [];
        foreach (Fixtures::activityFeed() as $item) {
            $activityRows[] = [
                $item['actor'],
                $item['summary'],
                $item['at'],
            ];
        }

        $page = Page::make(
            MetricGrid::make(
                MetricCard::make()->title('Active users')->value((string) count(array_filter($users, static fn (array $u): bool => $u['status'] === 'active')))->trend('+4.2%', 'up')->icon('users')->color('primary'),
                MetricCard::make()->title('MRR')->value($revenue['mrr'])->trend('+1.8%', 'up')->icon('banknotes')->color('success'),
                MetricCard::make()->title('Open orders')->value((string) count(array_filter($orders, static fn (array $o): bool => in_array($o['status'], ['pending', 'processing'], true))))->trend('-6%', 'down')->icon('shopping-cart')->color('warning'),
                MetricCard::make()->title('Unread alerts')->value((string) count(array_filter(Fixtures::notifications(), static fn (array $n): bool => $n['status'] === 'unread')))->icon('bell')->color('danger'),
            ),
            InfoCard::make(
                Row::make([
                    Statistic::make('ARR', $revenue['arr']),
                    Statistic::make('Avg. order', $revenue['avgOrder']),
                    Statistic::make('Refund rate', $revenue['refundRate']),
                    Statistic::make('Conversion', $revenue['conversion']),
                    Statistic::make('NPS', $revenue['nps']),
                ])->gap(6),
            )->title('Revenue summary'),
            Toolbar::make(
                ToolbarAction::make('Invite user')->icon('user-plus')->primary()->href('?page=users'),
                ToolbarAction::make('New order')->icon('plus')->href('?page=orders'),
                ToolbarAction::make('View reports')->icon('chart-bar')->href('?page=reports'),
                ToolbarAction::make('Audit log')->icon('clipboard-document-check')->href('?page=audit-logs'),
            ),
            InfoCard::make(
                DataTable::make()
                    ->headers(['User', 'Role', 'Status', 'Last active'])
                    ->rows($recentUserRows)
                    ->hover(),
            )->title('Recent users'),
            InfoCard::make(
                DataTable::make()
                    ->headers(['Order', 'Customer', 'Total', 'Status', 'Placed', ''])
                    ->rows($recentOrderRows)
                    ->hover(),
            )->title('Recent orders'),
            InfoCard::make(
                DataTable::make()
                    ->headers(['Actor', 'Activity', 'When'])
                    ->rows($activityRows)
                    ->hover(),
            )->title('Activity feed'),
        )
            ->breadcrumb(Breadcrumb::make(BreadcrumbItem::make('Dashboard')->current()))
            ->title('Dashboard')
            ->description('An overview of your workspace, right now.')
            ->actions(ToolbarAction::make('Refresh')->icon('arrow-path')->href('?page=dashboard'));

        return PanelLayout::wrap('dashboard', $page);
    }
}
