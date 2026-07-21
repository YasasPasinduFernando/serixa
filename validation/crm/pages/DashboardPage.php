<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\ActivityItem;
use Serixa\Component\ActivityList;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Dropdown;
use Serixa\Component\DropdownItem;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\StatCard;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * CRM dashboard with KPIs and recent activity.
 */
final class DashboardPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Dashboard')
                ->subtitle('Pipeline overview')
                ->actions(
                    Dropdown::make()
                        ->trigger(Button::make('Range')->secondary()->small())
                        ->items([
                            DropdownItem::make('Last 7 days')->href('/?page=dashboard&range=7d'),
                            DropdownItem::make('Last 30 days')->href('/?page=dashboard&range=30d'),
                            DropdownItem::make('This quarter')->href('/?page=dashboard&range=q'),
                        ]),
                ),
            Row::make([
                StatCard::make()->title('Customers')->value('248')->trend('+12%')->color('primary'),
                StatCard::make()->title('Open deals')->value('$186k')->trend('+4.1%')->color('success'),
                StatCard::make()->title('Invoices due')->value('17')->subtitle('Next 14 days')->color('warning'),
                StatCard::make()->title('Tasks overdue')->value('5')->color('danger'),
            ])->gap(4),
            Text::make('Recent activity')->as('h2')->size('lg')->weight('semibold'),
            ActivityList::make(
                ActivityItem::make()
                    ->title('Invoice INV-1042 paid')
                    ->description('Acme Corp · $4,200')
                    ->timestamp('1h ago')
                    ->icon('•'),
                ActivityItem::make()
                    ->title('New customer added')
                    ->description('Jordan Lee')
                    ->timestamp('3h ago')
                    ->icon('•'),
                ActivityItem::make()
                    ->title('Task completed')
                    ->description('Follow up with Northwind')
                    ->timestamp('Yesterday')
                    ->icon('•'),
            ),
        ])->gap(4);

        return Document::make()
            ->title('Dashboard · Serixa CRM')
            ->description('CRM dashboard')
            ->language('en')
            ->body(CrmLayout::shell('dashboard', $body));
    }
}
