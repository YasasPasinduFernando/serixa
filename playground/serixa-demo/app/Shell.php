<?php

declare(strict_types=1);

namespace SerixaDemo;

use Serixa\Component\AppBar;
use Serixa\Component\Button;
use Serixa\Component\Divider;
use Serixa\Component\Footer;
use Serixa\Component\Link;
use Serixa\Component\MainContent;
use Serixa\Component\Scaffold;
use Serixa\Component\Sidebar;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

/**
 * App chrome using UI Foundation widgets (Scaffold, AppBar, Sidebar, Footer, Link).
 */
final class Shell
{
    public static function app(string $active, WidgetInterface $content): WidgetInterface
    {
        return Scaffold::make()
            ->appBar(
                AppBar::make()
                    ->title('SERIXA Demo')
                    ->actions(
                        self::navLink('Dashboard', 'dashboard', $active),
                        self::navLink('CRUD', 'crud', $active),
                        self::navLink('Interactive', 'interactive', $active),
                        self::navLink('Settings', 'settings', $active),
                        self::navLink('Profile', 'profile', $active),
                        Button::make('Logout')->ghost()->small(),
                    ),
            )
            ->sidebar(
                Sidebar::make(
                    Text::make('Menu')->as('h2')->size('sm')->weight('semibold')->tone('muted'),
                    self::navLink('Dashboard', 'dashboard', $active),
                    self::navLink('Users (CRUD)', 'crud', $active),
                    self::navLink('Interactive', 'interactive', $active),
                    self::navLink('Settings', 'settings', $active),
                    self::navLink('Profile', 'profile', $active),
                    Divider::make()->spacing('sm'),
                    self::navLink('Login', 'login', $active),
                )->ariaLabel('Primary'),
            )
            ->body(MainContent::make($content))
            ->footer(Footer::make('SERIXA Demo · Interactive Components (v0.9.0)'));
    }

    private static function navLink(string $label, string $page, string $active): WidgetInterface
    {
        return Link::make($label)
            ->href('/?page=' . $page)
            ->active($page === $active);
    }
}
