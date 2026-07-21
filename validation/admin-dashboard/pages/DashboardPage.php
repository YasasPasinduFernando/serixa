<?php

declare(strict_types=1);

namespace App\Pages;

use App\Components\StatRow;
use App\Layouts\AdminLayout;
use Serixa\Component\ActivityItem;
use Serixa\Component\ActivityList;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\StatCard;
use Serixa\Component\Tab;
use Serixa\Component\TabPanel;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Component\Toast;
use Serixa\Document\Document;

final class DashboardPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()
                ->title('Dashboard')
                ->subtitle('Workspace overview for the last 7 days.')
                ->actions(
                    Dropdown::make()
                        ->trigger(Button::make('Actions')->secondary())
                        ->items([
                            DropdownItem::make('Refresh')->href('/?page=dashboard'),
                            DropdownItem::make('Export')->href('/?page=dashboard&export=1'),
                        ]),
                    Button::make('Notify')
                        ->ghost()
                        ->attribute('data-serixa-show-toast', 'admin-dash-toast'),
                ),
            SizedBox::height(2),
            Row::make([
                Badge::make('Live')->color('success'),
                Badge::make('Admin')->color('primary'),
                Text::make('SSR + ClientRuntime')->size('sm')->tone('muted'),
            ])->gap(2)->alignCenter(),
            SizedBox::height(3),
            StatRow::make(
                StatCard::make()->title('Users')->value('1,284')->trend('+3.1%')->color('primary'),
                StatCard::make()->title('Sessions')->value('9,441')->trend('+12%')->color('success'),
                StatCard::make()->title('Errors')->value('12')->subtitle('Last 24h')->color('warning'),
                StatCard::make()->title('Uptime')->value('99.98%')->color('default'),
            ),
            SizedBox::height(4),
            Tabs::make()
                ->tab(
                    Tab::make('Activity')
                        ->active()
                        ->content(
                            TabPanel::make()->content(
                                Card::make(
                                    ActivityList::make(
                                        ActivityItem::make()->title('Ada invited a teammate')->timestamp('2h ago')->icon('•'),
                                        ActivityItem::make()->title('Role “Editor” updated')->description('Permissions changed')->timestamp('Yesterday')->icon('•'),
                                        ActivityItem::make()->title('API key rotated')->timestamp('3d ago')->icon('•'),
                                    ),
                                )->title('Recent activity')->padding('md'),
                            ),
                        ),
                )
                ->tab(
                    Tab::make('Summary')
                        ->content(
                            TabPanel::make()->content(
                                Text::make('Summary panel — traffic charts are not in the public widget set yet.')
                                    ->tone('muted'),
                            ),
                        ),
                ),
            Toast::make()
                ->id('admin-dash-toast')
                ->message('Dashboard refreshed')
                ->tone('success')
                ->open(false),
        ])->gap(2)->alignStart();

        return Document::make()
            ->title('Dashboard · Admin')
            ->body(AdminLayout::shell('dashboard', $content));
    }
}
