<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
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
use Serixa\Component\Select;
use Serixa\Document\Document;

final class NotificationsPage
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

        foreach (Fixtures::notifications() as $notification) {
            $rows[] = [
                $notification['title'],
                $notification['channel'],
                Presenter::notificationStatusBadge($notification['status']),
                $notification['receivedAt'],
                RowActions::make()->link('Open', '#')->link('Dismiss', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search notifications…'),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'unread' => 'Unread',
                    'read' => 'Read',
                ]),
                Select::make()->name('channel')->placeholder('All channels')->options([
                    'email' => 'Email',
                    'in-app' => 'In-app',
                    'slack' => 'Slack',
                ]),
                ColumnVisibility::make()
                    ->column('Notification')
                    ->column('Channel')
                    ->column('Status')
                    ->column('Received'),
            ),
            BulkActions::make(
                ToolbarAction::make('Mark read')->icon('check'),
                ToolbarAction::make('Dismiss')->icon('x-mark')->danger(),
            )->summary('3 notifications selected'),
            DataTable::make()
                ->headers(['Notification', 'Channel', 'Status', 'Received', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('bell')
                        ->title('All caught up')
                        ->description('New notifications will appear in this inbox.'),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Notifications')->current(),
            ))
            ->title('Notifications')
            ->description('Alerts across email, in-app, and chat channels.')
            ->actions(ToolbarAction::make('Mark all read')->icon('check')->href('#'));

        return PanelLayout::wrap('notifications', $page);
    }
}
