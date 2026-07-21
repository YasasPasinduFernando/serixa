<?php

declare(strict_types=1);

namespace App;

use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Component\Avatar;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;

/**
 * Shared chrome for every page: sidebar, top bar and footer composed from
 * {@see Serixa\Admin\Layout\AdminLayout} (AppShell under the hood).
 */
final class PanelLayout
{
    /** @var array<string, string> */
    private const TITLES = [
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'departments' => 'Departments',
        'products' => 'Products',
        'orders' => 'Orders',
        'customers' => 'Customers',
        'invoices' => 'Invoices',
        'reports' => 'Reports',
        'analytics' => 'Analytics',
        'notifications' => 'Notifications',
        'audit-logs' => 'Audit Logs',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'not-found' => 'Not Found',
    ];

    private function __construct()
    {
    }

    public static function wrap(string $active, WidgetInterface $pageContent): Document
    {
        $shell = AdminLayout::shell()
            ->topBar(self::topBar())
            ->sidebar(self::sidebar($active))
            ->content(MainContent::make(PageContainer::make($pageContent)))
            ->footer(FooterBar::make(
                '© 2026 SERIXA Admin Starter — built with serixa/framework, serixa/admin & serixa/icons. UI only, no real data.',
            ));

        return DocumentFactory::make(self::TITLES[$active] ?? 'SERIXA Admin', $shell);
    }

    private static function topBar(): TopBar
    {
        return TopBar::make()
            ->brand('SERIXA Admin')
            ->actions(
                UserMenu::make('Ada Lovelace')
                    ->avatar(Avatar::make()->initials('AL')->size('sm')->circle())
                    ->item('Profile', '?page=profile')
                    ->item('Settings', '?page=settings')
                    ->divider()
                    ->item('Sign out', '#', danger: true),
            );
    }

    private static function sidebar(string $active): Sidebar
    {
        return Sidebar::make()->ariaLabel('Admin navigation')->children(
            SidebarGroup::make()->label('Overview')->items(
                SidebarItem::make('Dashboard')->icon('home')->href('?page=dashboard')->active($active === 'dashboard'),
                SidebarItem::make('Analytics')->icon('chart-pie')->href('?page=analytics')->active($active === 'analytics'),
                SidebarItem::make('Reports')->icon('chart-bar')->href('?page=reports')->active($active === 'reports'),
            ),
            SidebarGroup::make()->label('People')->items(
                SidebarItem::make('Users')->icon('users')->href('?page=users')->active($active === 'users'),
                SidebarItem::make('Roles')->icon('shield-check')->href('?page=roles')->active($active === 'roles'),
                SidebarItem::make('Permissions')->icon('key')->href('?page=permissions')->active($active === 'permissions'),
                SidebarItem::make('Departments')->icon('building-office')->href('?page=departments')->active($active === 'departments'),
                SidebarItem::make('Customers')->icon('user-plus')->href('?page=customers')->active($active === 'customers'),
            ),
            SidebarGroup::make()->label('Commerce')->items(
                SidebarItem::make('Products')->icon('cube')->href('?page=products')->active($active === 'products'),
                SidebarItem::make('Orders')->icon('shopping-cart')->href('?page=orders')->active($active === 'orders'),
                SidebarItem::make('Invoices')->icon('receipt-percent')->href('?page=invoices')->active($active === 'invoices'),
            ),
            SidebarGroup::make()->label('System')->items(
                SidebarItem::make('Notifications')->icon('bell')->href('?page=notifications')->active($active === 'notifications')->badge('3'),
                SidebarItem::make('Audit Logs')->icon('clipboard-document-check')->href('?page=audit-logs')->active($active === 'audit-logs'),
                SidebarItem::make('Settings')->icon('cog-6-tooth')->href('?page=settings')->active($active === 'settings'),
                SidebarItem::make('Profile')->icon('user-circle')->href('?page=profile')->active($active === 'profile'),
            ),
        );
    }
}
