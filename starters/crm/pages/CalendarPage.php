<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Calendar\Component\MonthView;
use Serixa\Calendar\Event\Event;
use Serixa\Document\Document;

final class CalendarPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $events = [];

        foreach (Fixtures::calendarEvents() as $event) {
            $events[] = Event::make($event['title'])
                ->start($event['start'])
                ->end($event['end'])
                ->color($event['color']);
        }

        $page = Page::make(
            InfoCard::make(
                MonthView::make()
                    ->focus('2026-07-01')
                    ->today('2026-07-22')
                    ->events($events)
                    ->summary('CRM calendar'),
            )->title('July 2026'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Calendar')->current(),
            ))
            ->title('Calendar')
            ->description('Meetings and events for the sales team.')
            ->actions(ToolbarAction::make('New meeting')->icon('plus')->primary()->href('?page=meetings'));

        return PanelLayout::wrap('calendar', $page);
    }
}
