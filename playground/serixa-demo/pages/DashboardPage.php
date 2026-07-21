<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;
use SerixaDemo\Shell;

final class DashboardPage
{
    public static function document(): Document
    {
        $stats = Row::make([
            self::statCard('Users', '1,284'),
            self::statCard('Revenue', '$48.2k'),
            self::statCard('Sessions', '9,441'),
            self::statCard('Churn', '2.1%'),
        ])->gap(4)->spaceBetween();

        $main = Column::make([
            Text::make('Dashboard')->as('h1')->size('2xl')->weight('bold'),
            Text::make('Overview of the last 7 days.')->tone('muted'),
            SizedBox::height(2),
            $stats,
            SizedBox::height(4),
            // Missing: Chart
            Missing::widget('Chart', 'Line/bar chart placeholder for traffic'),
            SizedBox::height(4),
            Card::make(
                Text::make('Recent activity')->as('h2')->size('lg')->weight('semibold'),
                // Missing: List / ActivityFeed / Timeline
                Missing::widget('ActivityList', 'Feed of recent user actions'),
                Text::make('Ada joined the workspace')->size('sm'),
                Text::make('Invoice #1042 paid')->size('sm')->tone('muted'),
                Text::make('API key rotated')->size('sm')->tone('muted'),
            )->padding('md'),
            SizedBox::height(2),
            Row::make([
                Button::make('Refresh')->secondary(),
                Button::make('Export')->ghost(),
            ])->gap(2),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'Dashboard',
            Shell::app('dashboard', $main),
        );
    }

    private static function statCard(string $label, string $value): Card
    {
        // Missing: StatCard / Metric widget — Card + Text composition is repetitive.
        return Card::make(
            Text::make($label)->size('sm')->tone('muted'),
            Text::make($value)->as('p')->size('2xl')->weight('bold'),
        )->padding('md');
    }
}
