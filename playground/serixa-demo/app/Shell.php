<?php

declare(strict_types=1);

namespace SerixaDemo;

use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Divider;
use Serixa\Component\Expanded;
use Serixa\Component\Padding;
use Serixa\Component\Row;
use Serixa\Component\Spacer;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

/**
 * Approximate app chrome with existing layout widgets only.
 * Missing: Sidebar, AppBar, Nav, Footer as first-class widgets.
 */
final class Shell
{
    public static function app(string $active, WidgetInterface $content): WidgetInterface
    {
        return Column::make([
            self::topNav($active),
            Row::make([
                self::sidebar($active),
                Expanded::make(
                    Padding::all(6, $content),
                ),
            ])->alignStart(),
            self::footer(),
        ]);
    }

    private static function topNav(string $active): WidgetInterface
    {
        // DX: Padding has static factories only (no ->symmetric()). Nested x/y is verbose.
        return Padding::y(
            4,
            Padding::x(
                6,
                Row::make([
                    Text::make('SERIXA')->as('span')->weight('bold')->size('lg')->tone('primary'),
                    Text::make('Demo')->as('span')->tone('muted')->size('sm'),
                    Spacer::make(),
                    self::navLink('Dashboard', 'dashboard', $active),
                    self::navLink('CRUD', 'crud', $active),
                    self::navLink('Settings', 'settings', $active),
                    self::navLink('Profile', 'profile', $active),
                    Button::make('Logout')->ghost()->small(),
                ])->alignCenter()->gap(4)->spaceBetween(),
            ),
        )->class('bg-slate-900 text-white border-b border-slate-800');
    }

    private static function sidebar(string $active): WidgetInterface
    {
        // Missing Sidebar widget — approximated with Column + Text links.
        return Padding::all(
            4,
            Column::make([
                Text::make('Menu')->as('h2')->size('sm')->weight('semibold')->tone('muted'),
                self::navLink('Dashboard', 'dashboard', $active),
                self::navLink('Users (CRUD)', 'crud', $active),
                self::navLink('Settings', 'settings', $active),
                self::navLink('Profile', 'profile', $active),
                Divider::make(),
                self::navLink('Login', 'login', $active),
            ])->gap(2)->alignStart(),
        )->class('w-56 min-h-[70vh] bg-slate-50 border-r border-slate-200');
    }

    private static function footer(): WidgetInterface
    {
        // Missing Footer widget.
        return Padding::y(
            4,
            Padding::x(
                6,
                Container::make(
                    Text::make('SERIXA Demo · Milestone 1–5 API surface')
                        ->as('p')
                        ->size('sm')
                        ->tone('muted'),
                )->maxWidth('full'),
            ),
        )->class('border-t border-slate-200 bg-white');
    }

    private static function navLink(string $label, string $page, string $active): WidgetInterface
    {
        // Missing Link / NavItem — Text::as('a') is the only navigation primitive.
        $text = Text::make($label)
            ->as('a')
            ->size('sm')
            ->attribute('href', '/?page=' . $page);

        if ($page === $active) {
            $text->weight('bold')->tone('primary');
        } else {
            $text->tone('muted');
        }

        return $text;
    }
}
