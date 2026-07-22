<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Assets\StyleSheet;

use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\Cards\Statistic;
use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\Toolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Component\Row;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$sidebar = Sidebar::make()->children(
    SidebarGroup::make()->label('Overview')->items(
        SidebarItem::make('Dashboard')->icon('home')->href('/admin')->active(),
        SidebarItem::make('Users')->icon('users')->href('/admin/users'),
        SidebarItem::make('Reports')->icon('chart-bar')->href('/admin/reports'),
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

$page = Page::make(
    MetricGrid::make(
        MetricCard::make()->title('Active users')->value('12,480')->trend('+4.2%', 'up')->icon('users')->color('primary'),
        MetricCard::make()->title('Revenue')->value('$48,120')->trend('+1.8%', 'up')->icon('banknotes')->color('success'),
        MetricCard::make()->title('Open tickets')->value('37')->trend('-6%', 'down')->icon('inbox')->color('warning'),
        MetricCard::make()->title('Churn')->value('1.2%')->trend('flat', 'neutral')->icon('chart-pie'),
    ),
    Row::make([
        InfoCard::make(
            Row::make([
                Statistic::make('Avg. session', '4m 12s'),
                Statistic::make('Signups today', '58'),
                Statistic::make('Uptime', '99.98%'),
            ])->gap(6),
        )->title('This week'),
    ])->gap(4),
    Toolbar::make(
        ToolbarAction::make('Invite user')->icon('user-plus')->primary()->href('/admin/users'),
        ToolbarAction::make('View reports')->icon('chart-bar')->href('/admin/reports'),
    ),
)
    ->breadcrumb(Breadcrumb::make(BreadcrumbItem::make('Dashboard')->current()))
    ->title('Dashboard')
    ->description('An overview of your workspace, right now.');

$shell = AdminLayout::shell()
    ->topBar($topBar)
    ->sidebar($sidebar)
    ->content(MainContent::make(PageContainer::make($page)))
    ->footer(FooterBar::make('© 2026 Acme Inc. — serixa/admin dashboard example'));

$document = Document::make()->title('Dashboard · Acme Admin')->body($shell);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
