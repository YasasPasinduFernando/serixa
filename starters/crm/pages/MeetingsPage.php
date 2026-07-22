<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\BulkActions;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

final class MeetingsPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $rows = [];

        foreach (Fixtures::meetings() as $meeting) {
            $rows[] = [
                $meeting['title'],
                $meeting['company'],
                $meeting['start'],
                $meeting['end'],
                $meeting['location'],
                $meeting['owner'],
                RowActions::make()->link('Join', '#')->link('Reschedule', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search meetings…'),
            TableFilters::make(
                ColumnVisibility::make()
                    ->column('Meeting')
                    ->column('Company')
                    ->column('Start')
                    ->column('End')
                    ->column('Location')
                    ->column('Owner'),
            ),
            BulkActions::make(
                ToolbarAction::make('Cancel')->icon('trash')->danger(),
            )->summary('1 meeting selected'),
            DataTable::make()
                ->headers(['Meeting', 'Company', 'Start', 'End', 'Location', 'Owner', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No meetings')
                        ->description('Schedule discovery calls and demos from here.')
                        ->action(ToolbarAction::make('New meeting')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Meetings')->current(),
            ))
            ->title('Meetings')
            ->description('Upcoming and recent customer conversations.')
            ->actions(ToolbarAction::make('New meeting')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('meetings', $page);
    }
}
