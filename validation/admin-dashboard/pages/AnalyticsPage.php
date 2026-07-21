<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Alert;
use Serixa\Component\Column;
use Serixa\Component\Metric;
use Serixa\Component\PageHeader;
use Serixa\Component\Progress;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Skeleton;
use Serixa\Component\Spinner;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class AnalyticsPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()
                ->title('Analytics')
                ->subtitle('Key metrics and loading states.'),
            SizedBox::height(2),
            Alert::make()
                ->tone('info')
                ->title('Sample data')
                ->body('Metrics below are static SSR placeholders.'),
            SizedBox::height(3),
            Row::make([
                Metric::make()->label('Avg latency')->value('42ms'),
                Metric::make()->label('Error rate')->value('0.04%'),
                Metric::make()->label('Throughput')->value('1.2k rps'),
                Metric::make()->label('Uptime')->value('99.98%'),
            ])->gap(6)->spaceBetween(),
            SizedBox::height(4),
            Progress::make()->value(72)->max(100)->label('Quota used')->size('md'),
            SizedBox::height(3),
            Row::make([
                Spinner::make()->size('sm')->label('Refreshing'),
                Text::make('Fetching latest series…')->size('sm')->tone('muted'),
            ])->gap(3)->alignCenter(),
            SizedBox::height(3),
            Text::make('Chart placeholder')->as('h2')->size('lg')->weight('semibold'),
            Skeleton::make()->variant('card'),
            Skeleton::make()->lines(3),
        ])->gap(2)->alignStart();

        return Document::make()
            ->title('Analytics · Admin')
            ->body(AdminLayout::shell('analytics', $content));
    }
}
