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
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;

/**
 * The shared chrome for every page of the blog starter: sidebar, top bar and
 * footer, all composed from {@see Serixa\Admin\Layout\AdminLayout}.
 *
 * @example BlogLayout::wrap('dashboard', Page::make(...))
 */
final class BlogLayout
{
    /** @var array<string, string> */
    private const TITLES = [
        'dashboard' => 'Dashboard',
        'posts' => 'Posts',
        'create-post' => 'New Post',
        'edit-post' => 'Edit Post',
        'categories' => 'Categories',
        'tags' => 'Tags',
        'media' => 'Media',
        'comments' => 'Comments',
        'users' => 'Users',
        'profile' => 'Profile',
        'settings' => 'Settings',
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
                '© 2026 SERIXA Blog Starter — built with serixa/framework, serixa/admin & serixa/icons. UI only, no real data.',
            ));

        return DocumentFactory::make(self::TITLES[$active] ?? 'SERIXA Blog', $shell);
    }

    private static function topBar(): TopBar
    {
        return TopBar::make()
            ->brand('SERIXA Blog')
            ->actions(
                UserMenu::make('Ada Lovelace')
                    ->item('Profile', '?page=profile')
                    ->item('Settings', '?page=settings')
                    ->divider()
                    ->item('Sign out', '#', danger: true),
            );
    }

    private static function sidebar(string $active): Sidebar
    {
        return Sidebar::make()->children(
            SidebarGroup::make()->label('Content')->items(
                SidebarItem::make('Dashboard')->icon('home')->href('?page=dashboard')->active($active === 'dashboard'),
                SidebarItem::make('Posts')->icon('document-text')->href('?page=posts')->active(in_array($active, ['posts', 'create-post', 'edit-post'], true)),
                SidebarItem::make('Categories')->icon('folder')->href('?page=categories')->active($active === 'categories'),
                SidebarItem::make('Tags')->icon('tag')->href('?page=tags')->active($active === 'tags'),
                SidebarItem::make('Media')->icon('photo')->href('?page=media')->active($active === 'media'),
                SidebarItem::make('Comments')->icon('chat-bubble-left')->href('?page=comments')->active($active === 'comments'),
            ),
            SidebarGroup::make()->label('People')->items(
                SidebarItem::make('Users')->icon('users')->href('?page=users')->active($active === 'users'),
            ),
            SidebarGroup::make()->label('Account')->items(
                SidebarItem::make('Profile')->icon('user-circle')->href('?page=profile')->active($active === 'profile'),
                SidebarItem::make('Settings')->icon('cog-6-tooth')->href('?page=settings')->active($active === 'settings'),
            ),
        );
    }
}
