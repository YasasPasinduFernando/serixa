<?php

declare(strict_types=1);

namespace App\Layouts;

use App\Components\CrmNav;
use Serixa\Component\AppBar;
use Serixa\Component\Button;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\Footer;
use Serixa\Component\Link;
use Serixa\Component\MainContent;
use Serixa\Component\Scaffold;
use Serixa\Component\Sidebar;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Styling\StyleSheet;
use Serixa\Runtime\ClientRuntime;

/**
 * Shared CRM chrome: assets, ClientRuntime, Scaffold shell.
 */
final class CrmLayout
{
    public static function wrap(Document $document): Document
    {
        StyleSheet::register($document->assets());

        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    public static function shell(string $active, WidgetInterface $content): WidgetInterface
    {
        return Scaffold::make()
            ->appBar(
                AppBar::make()
                    ->title('Serixa CRM')
                    ->actions(
                        Dropdown::make()
                            ->trigger(Button::make('Account')->ghost()->small())
                            ->items([
                                DropdownItem::make('Profile')->href('/?page=profile'),
                                DropdownItem::make('Settings')->href('/?page=settings'),
                                DropdownItem::make('Sign out')->href('/?page=login')->danger(),
                            ]),
                    ),
            )
            ->sidebar(
                Sidebar::make(...CrmNav::links($active))->ariaLabel('CRM navigation'),
            )
            ->body(
                MainContent::make($content),
            )
            ->footer(
                Footer::make(
                    Link::make('© 2026 Serixa CRM')->href('/?page=dashboard'),
                ),
            );
    }
}
