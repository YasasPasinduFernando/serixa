<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\ActivityItem;
use Serixa\Component\ActivityList;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\Metric;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Component\Toast;
use Serixa\Document\Document;
use SerixaDemo\Components\PageSection;
use SerixaDemo\Components\StatGrid;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;
use SerixaDemo\Shell;

final class DashboardPage
{
    public static function document(): Document
    {
        $main = PageSection::make(
            'Dashboard',
            'Overview of the last 7 days.',
            [
                Dropdown::make()
                    ->trigger(Button::make('Actions')->secondary())
                    ->items([
                        DropdownItem::make('Refresh')->href('/?page=dashboard'),
                        DropdownItem::make('Export')->href('/?page=dashboard&export=1'),
                    ]),
                Button::make('Notify')
                    ->ghost()
                    ->attribute('data-serixa-show-toast', 'dash-toast'),
            ],
            Row::make([
                Badge::make('Live')->color('success'),
                Badge::make('SSR')->color('primary'),
                Text::make('Interactive PE widgets')->size('sm')->tone('muted'),
            ])->gap(2)->alignCenter(),
            SizedBox::height(2),
            StatGrid::make(
                ['title' => 'Users', 'value' => '1,284', 'trend' => '+3.1%', 'color' => 'primary'],
                ['title' => 'Revenue', 'value' => '$48.2k', 'subtitle' => 'Last 7 days', 'color' => 'success'],
                ['title' => 'Sessions', 'value' => '9,441', 'trend' => '+12%'],
                ['title' => 'Churn', 'value' => '2.1%', 'color' => 'warning'],
            ),
            SizedBox::height(3),
            Row::make([
                Metric::make()->label('Avg latency')->value('42ms'),
                Metric::make()->label('Error rate')->value('0.04%'),
                Metric::make()->label('Uptime')->value('99.98%'),
            ])->gap(6)->spaceBetween(),
            SizedBox::height(4),
            Tabs::make()
                ->tab(
                    Tab::make('Activity')
                        ->active()
                        ->content(
                            Card::make(
                                ActivityList::make(
                                    ActivityItem::make()->title('Ada joined the workspace')->timestamp('2h ago')->icon('•'),
                                    ActivityItem::make()->title('Invoice #1042 paid')->description('Pro plan')->timestamp('Yesterday')->icon('•'),
                                    ActivityItem::make()->title('API key rotated')->timestamp('3d ago')->icon('•'),
                                ),
                            )->title('Recent activity')->padding('md'),
                        ),
                )
                ->tab(
                    Tab::make('Traffic')
                        ->content(Missing::widget('Chart', 'Line/bar chart placeholder for traffic')),
                ),
            Toast::make()->id('dash-toast')->message('Dashboard refreshed')->tone('success')->open(false),
        );

        return DocumentFactory::page(
            'Dashboard',
            Shell::app('dashboard', $main),
        );
    }
}
