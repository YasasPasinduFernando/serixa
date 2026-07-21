<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\Statistic;
use Serixa\Admin\Components\DescriptionItem;
use Serixa\Admin\Components\DescriptionList;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Component\Row;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$sidebar = Sidebar::make()->children(
    SidebarGroup::make()->label('Overview')->items(
        SidebarItem::make('Dashboard')->icon('home')->href('/admin'),
        SidebarItem::make('Users')->icon('users')->href('/admin/users'),
        SidebarItem::make('Reports')->icon('chart-bar')->href('/admin/reports'),
    ),
    SidebarGroup::make()->label('Workspace')->items(
        SidebarItem::make('Settings')->icon('cog-6-tooth')->href('/admin/settings')->active(),
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
    InfoCard::make(
        DescriptionList::make(
            DescriptionItem::make('Workspace name', 'Acme Inc.'),
            DescriptionItem::make('Plan', StatusBadge::make('Pro')->status('info')),
            DescriptionItem::make('Owner', 'Ada Lovelace'),
            DescriptionItem::make('Created', 'Jan 12, 2025'),
        ),
    )->title('Workspace'),
    InfoCard::make(
        Row::make([
            Statistic::make('Seats used', '24 / 50'),
            Statistic::make('Storage used', '128 GB / 500 GB'),
            Statistic::make('API requests (30d)', '1.2M'),
        ])->gap(6),
    )->title('Usage'),
    InfoCard::make(
        DescriptionList::make(
            DescriptionItem::make('Two-factor authentication', StatusBadge::make('Enabled')->status('success')),
            DescriptionItem::make('SSO', StatusBadge::make('Not configured')->status('neutral')),
            DescriptionItem::make('Audit log retention', '90 days'),
        ),
    )->title('Security'),
    InfoCard::make(
        Row::make([
            ToolbarAction::make('Transfer ownership')->icon('arrow-path'),
            ToolbarAction::make('Delete workspace')->icon('trash')->danger(),
        ])->gap(3),
    )->title('Danger zone'),
)
    ->breadcrumb(Breadcrumb::make(
        BreadcrumbItem::make('Dashboard')->href('/admin'),
        BreadcrumbItem::make('Settings')->current(),
    ))
    ->title('Settings')
    ->description('Workspace-level preferences. UI only — no persistence in this example.');

$shell = AdminLayout::shell()
    ->topBar($topBar)
    ->sidebar($sidebar)
    ->content(MainContent::make(PageContainer::make($page)->maxWidth('lg')))
    ->footer(FooterBar::make('© 2026 Acme Inc. — serixa/admin settings example'));

$document = Document::make()->title('Settings · Acme Admin')->body($shell);
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
