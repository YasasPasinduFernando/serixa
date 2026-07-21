<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\ActivityItem;
use Serixa\Component\ActivityList;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\StatCard;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;
use SerixaDemo\Shell;

final class DashboardPage
{
    public static function document(): Document
    {
        $stats = Row::make([
            StatCard::make()->title('Users')->value('1,284')->trend('+3.1%')->color('primary'),
            StatCard::make()->title('Revenue')->value('$48.2k')->subtitle('Last 7 days')->color('success'),
            StatCard::make()->title('Sessions')->value('9,441')->trend('+12%'),
            StatCard::make()->title('Churn')->value('2.1%')->color('warning'),
        ])->gap(4)->spaceBetween();

        $main = Column::make([
            PageHeader::make()
                ->title('Dashboard')
                ->subtitle('Overview of the last 7 days.')
                ->actions(
                    Button::make('Refresh')->secondary(),
                    Button::make('Export')->ghost(),
                ),
            SizedBox::height(2),
            $stats,
            SizedBox::height(4),
            Missing::widget('Chart', 'Line/bar chart placeholder for traffic'),
            SizedBox::height(4),
            Card::make(
                ActivityList::make(
                    ActivityItem::make()->title('Ada joined the workspace')->timestamp('2h ago')->icon('•'),
                    ActivityItem::make()->title('Invoice #1042 paid')->description('Pro plan')->timestamp('Yesterday')->icon('•'),
                    ActivityItem::make()->title('API key rotated')->timestamp('3d ago')->icon('•'),
                ),
            )->title('Recent activity')->padding('md'),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'Dashboard',
            Shell::app('dashboard', $main),
        );
    }
}
