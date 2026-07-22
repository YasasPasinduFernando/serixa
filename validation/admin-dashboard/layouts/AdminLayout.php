<?php

declare(strict_types=1);

namespace App\Layouts;

use Serixa\Component\AppBar;
use Serixa\Component\Link;
use Serixa\Component\MainContent;
use Serixa\Component\Padding;
use Serixa\Component\Scaffold;
use Serixa\Component\Sidebar;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Assets\StyleSheet;
use Serixa\Runtime\ClientRuntime;

/**
 * Admin shell: assets + Scaffold / AppBar / Sidebar chrome.
 */
final class AdminLayout
{
    private const NAV = [
        'dashboard' => 'Dashboard',
        'analytics' => 'Analytics',
        'users' => 'Users',
        'roles' => 'Roles',
        'crud' => 'CRUD',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'notifications' => 'Notifications',
        'login' => 'Login',
    ];

    public static function wrap(Document $document): Document
    {
        StyleSheet::register($document->assets());

        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    public static function shell(string $active, WidgetInterface $content): WidgetInterface
    {
        $links = [];
        $links[] = Text::make('Admin')->as('h2')->size('sm')->weight('semibold')->tone('muted');

        foreach (self::NAV as $page => $label) {
            $links[] = Link::make($label)
                ->href('/?page=' . $page)
                ->active($page === $active);
        }

        return Scaffold::make()
            ->appBar(
                AppBar::make()
                    ->title('Admin')
                    ->actions(
                        Link::make('Profile')->href('/?page=profile'),
                        Link::make('Login')->href('/?page=login'),
                    ),
            )
            ->sidebar(
                Sidebar::make(...$links)->ariaLabel('Admin'),
            )
            ->body(
                MainContent::make(
                    Padding::make($content)->all(6),
                ),
            );
    }
}
