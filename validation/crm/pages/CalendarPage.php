<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Column;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Document\Document;

/**
 * Calendar placeholder — no Calendar/Chart widget in the public API.
 */
final class CalendarPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Calendar')
                ->subtitle('Meetings and deadlines'),
            EmptyState::make()
                ->title('Calendar view not available')
                ->description(
                    'SERIXA has no Calendar or Chart widget in the public API. '
                    . 'This page is an intentional placeholder for validation debt tracking.',
                )
                ->action(
                    Link::make('Back to dashboard')->href('/?page=dashboard'),
                ),
        ])->gap(4);

        return Document::make()
            ->title('Calendar · Serixa CRM')
            ->description('Calendar placeholder')
            ->language('en')
            ->body(CrmLayout::shell('calendar', $body));
    }
}
