<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Styling\StyleSheet;

use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Components\StatusBadge;
use Serixa\Admin\Components\UserChip;
use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\BulkActions;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\ImportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$sidebar = Sidebar::make()->children(
    SidebarGroup::make()->label('Overview')->items(
        SidebarItem::make('Dashboard')->icon('home')->href('/admin'),
        SidebarItem::make('Users')->icon('users')->href('/admin/users')->active()->badge('482'),
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

$users = [
    ['name' => 'Ada Lovelace', 'email' => 'ada@example.com', 'role' => 'Owner', 'status' => 'success', 'statusLabel' => 'Active'],
    ['name' => 'Grace Hopper', 'email' => 'grace@example.com', 'role' => 'Admin', 'status' => 'success', 'statusLabel' => 'Active'],
    ['name' => 'Alan Turing', 'email' => 'alan@example.com', 'role' => 'Member', 'status' => 'warning', 'statusLabel' => 'Invited'],
    ['name' => 'Margaret Hamilton', 'email' => 'margaret@example.com', 'role' => 'Member', 'status' => 'neutral', 'statusLabel' => 'Suspended'],
];

$rows = [];

foreach ($users as $user) {
    $rows[] = [
        UserChip::make($user['name'])->subtitle($user['email']),
        $user['role'],
        StatusBadge::make($user['statusLabel'])->status($user['status']),
        RowActions::make()
            ->link('Edit', '/admin/users/1/edit')
            ->link('Delete', '/admin/users/1/delete'),
    ];
}

$table = DataTable::make()
    ->headers(['User', 'Role', 'Status', ''])
    ->rows($rows)
    ->hover();

$page = Page::make(
    SearchToolbar::make()
        ->placeholder('Search users by name or email…')
        ->actions(ImportButton::make(), ExportButton::make()),
    TableFilters::make(
        Select::make()->name('role')->placeholder('All roles')->options(['owner' => 'Owner', 'admin' => 'Admin', 'member' => 'Member']),
        Select::make()->name('status')->placeholder('All statuses')->options(['active' => 'Active', 'invited' => 'Invited', 'suspended' => 'Suspended']),
        ColumnVisibility::make()->column('User')->column('Role')->column('Status')->column('Joined', checked: false),
    ),
    BulkActions::make(
        ToolbarAction::make('Deactivate')->icon('lock-closed'),
        ToolbarAction::make('Delete')->icon('trash')->danger(),
    )->summary('2 users selected'),
    $table,
)
    ->breadcrumb(Breadcrumb::make(
        BreadcrumbItem::make('Dashboard')->href('/admin'),
        BreadcrumbItem::make('Users')->current(),
    ))
    ->title('Users')
    ->description('Manage who has access to your workspace.')
    ->actions(ToolbarAction::make('Invite user')->icon('user-plus')->primary()->href('/admin/users/invite'));

$shell = AdminLayout::shell()
    ->topBar($topBar)
    ->sidebar($sidebar)
    ->content(MainContent::make(PageContainer::make($page)))
    ->footer(FooterBar::make('© 2026 Acme Inc. — serixa/admin users example'));

$document = Document::make()->title('Users · Acme Admin')->body($shell);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
