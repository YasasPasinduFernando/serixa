<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Tables\TableToolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$sidebar = Sidebar::make()->children(
    SidebarGroup::make()->label('Overview')->items(
        SidebarItem::make('Dashboard')->icon('home')->href('/admin'),
        SidebarItem::make('Users')->icon('users')->href('/admin/users'),
        SidebarItem::make('Reports')->icon('chart-bar')->href('/admin/reports')->active(),
    ),
    SidebarGroup::make()->label('Workspace')->items(
        SidebarItem::make('Settings')->icon('cog-6-tooth')->href('/admin/settings'),
    ),
);

$topBar = TopBar::make()
    ->brand('Acme Admin')
    ->actions(
        UserMenu::make('Ada Lovelace')
            ->item('Profile', '/admin/profile')
            ->item('Settings', '/admin/settings')
            ->divider()
            ->item('Sign out', '/logout', danger: true),
    );

$reports = [
    ['name' => 'Monthly revenue', 'type' => 'Finance', 'generated' => 'Jul 1, 2026', 'status' => 'success', 'statusLabel' => 'Ready'],
    ['name' => 'User growth', 'type' => 'Growth', 'generated' => 'Jul 5, 2026', 'status' => 'success', 'statusLabel' => 'Ready'],
    ['name' => 'Churn analysis', 'type' => 'Growth', 'generated' => 'Jul 18, 2026', 'status' => 'warning', 'statusLabel' => 'Processing'],
    ['name' => 'Q2 compliance audit', 'type' => 'Compliance', 'generated' => '—', 'status' => 'danger', 'statusLabel' => 'Failed'],
];

$rows = [];

foreach ($reports as $report) {
    $rows[] = [
        $report['name'],
        $report['type'],
        $report['generated'],
        StatusBadge::make($report['statusLabel'])->status($report['status']),
        RowActions::make()
            ->link('Download', '/admin/reports/1/download')
            ->link('Delete', '/admin/reports/1/delete'),
    ];
}

$reportsTable = DataTable::make()
    ->headers(['Report', 'Type', 'Generated', 'Status', ''])
    ->rows($rows)
    ->hover()
    ->emptyState(
        EmptyState::make()
            ->icon('document-text')
            ->title('No reports yet')
            ->description('Generated reports will show up here.')
            ->action(ToolbarAction::make('Generate report')->icon('plus')->primary()),
    );

$page = Page::make(
    MetricGrid::make(
        MetricCard::make()->title('Generated this month')->value('18')->trend('+3', 'up')->icon('document-text')->color('primary'),
        MetricCard::make()->title('Scheduled')->value('5')->icon('clock'),
        MetricCard::make()->title('Failed')->value('1')->trend('+1', 'down')->icon('exclamation-triangle')->color('danger'),
    ),
    TableToolbar::make()->actions(ExportButton::make('Export all')),
    TableFilters::make(
        Select::make()->name('type')->placeholder('All types')->options([
            'finance' => 'Finance',
            'growth' => 'Growth',
            'compliance' => 'Compliance',
        ]),
        Select::make()->name('status')->placeholder('All statuses')->options([
            'ready' => 'Ready',
            'processing' => 'Processing',
            'failed' => 'Failed',
        ]),
    ),
    $reportsTable,
)
    ->breadcrumb(Breadcrumb::make(
        BreadcrumbItem::make('Dashboard')->href('/admin'),
        BreadcrumbItem::make('Reports')->current(),
    ))
    ->title('Reports')
    ->description('Generated and scheduled reports across your workspace.')
    ->actions(ToolbarAction::make('New report')->icon('plus')->primary()->href('/admin/reports/new'));

$shell = AdminLayout::shell()
    ->topBar($topBar)
    ->sidebar($sidebar)
    ->content(MainContent::make(PageContainer::make($page)))
    ->footer(FooterBar::make('© 2026 Acme Inc. — serixa/admin reports example'));

$document = Document::make()->title('Reports · Acme Admin')->body($shell);
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
