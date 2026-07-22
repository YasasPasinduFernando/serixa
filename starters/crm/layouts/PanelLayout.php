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
 * Shared CRM chrome: sidebar, top bar, footer via AdminLayout.
 */
final class PanelLayout
{
    /** @var array<string, string> */
    private const TITLES = [
        'dashboard' => 'Dashboard',
        'leads' => 'Leads',
        'lead' => 'Lead details',
        'companies' => 'Companies',
        'contacts' => 'Contacts',
        'opportunities' => 'Opportunities',
        'pipeline' => 'Pipeline',
        'tasks' => 'Tasks',
        'calendar' => 'Calendar',
        'meetings' => 'Meetings',
        'invoices' => 'Invoices',
        'reports' => 'Reports',
        'analytics' => 'Analytics',
        'users' => 'Users',
        'roles' => 'Roles',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'notifications' => 'Notifications',
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
                '© 2026 SERIXA CRM Starter — fixtures only, no database. Built with serixa/framework + admin + forms + icons + charts + calendar.',
            ));

        return DocumentFactory::make(self::TITLES[$active] ?? 'SERIXA CRM', $shell);
    }

    private static function topBar(): TopBar
    {
        return TopBar::make()
            ->brand('SERIXA CRM')
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
        return Sidebar::make()->ariaLabel('CRM navigation')->children(
            SidebarGroup::make()->label('Overview')->items(
                SidebarItem::make('Dashboard')->icon('home')->href('?page=dashboard')->active($active === 'dashboard'),
                SidebarItem::make('Pipeline')->icon('funnels')->href('?page=pipeline')->active($active === 'pipeline'),
                SidebarItem::make('Analytics')->icon('chart-pie')->href('?page=analytics')->active($active === 'analytics'),
                SidebarItem::make('Reports')->icon('chart-bar')->href('?page=reports')->active($active === 'reports'),
            ),
            SidebarGroup::make()->label('Sales')->items(
                SidebarItem::make('Leads')->icon('user-plus')->href('?page=leads')->active(in_array($active, ['leads', 'lead'], true)),
                SidebarItem::make('Opportunities')->icon('briefcase')->href('?page=opportunities')->active($active === 'opportunities'),
                SidebarItem::make('Companies')->icon('building-office')->href('?page=companies')->active($active === 'companies'),
                SidebarItem::make('Contacts')->icon('users')->href('?page=contacts')->active($active === 'contacts'),
            ),
            SidebarGroup::make()->label('Activity')->items(
                SidebarItem::make('Tasks')->icon('clipboard-document-check')->href('?page=tasks')->active($active === 'tasks'),
                SidebarItem::make('Calendar')->icon('calendar')->href('?page=calendar')->active($active === 'calendar'),
                SidebarItem::make('Meetings')->icon('camera')->href('?page=meetings')->active($active === 'meetings'),
            ),
            SidebarGroup::make()->label('Finance')->items(
                SidebarItem::make('Invoices')->icon('receipt-percent')->href('?page=invoices')->active($active === 'invoices'),
            ),
            SidebarGroup::make()->label('System')->items(
                SidebarItem::make('Notifications')->icon('bell')->href('?page=notifications')->active($active === 'notifications')->badge('3'),
                SidebarItem::make('Users')->icon('user-circle')->href('?page=users')->active($active === 'users'),
                SidebarItem::make('Roles')->icon('shield-check')->href('?page=roles')->active($active === 'roles'),
                SidebarItem::make('Settings')->icon('cog-6-tooth')->href('?page=settings')->active($active === 'settings'),
                SidebarItem::make('Profile')->icon('user-circle')->href('?page=profile')->active($active === 'profile'),
            ),
        );
    }
}
