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

final class TasksPage
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

        foreach (Fixtures::tasks() as $task) {
            $rows[] = [
                $task['title'],
                $task['related'],
                $task['due'],
                Presenter::taskPriorityBadge($task['priority']),
                Presenter::taskStatusBadge($task['status']),
                $task['owner'],
                RowActions::make()->link('Complete', '#')->link('Edit', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search tasks…'),
            TableFilters::make(
                Select::make()->name('priority')->placeholder('All priorities')->options([
                    'high' => 'High',
                    'medium' => 'Medium',
                    'low' => 'Low',
                ]),
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'open' => 'Open',
                    'done' => 'Done',
                ]),
                ColumnVisibility::make()
                    ->column('Task')
                    ->column('Related')
                    ->column('Due')
                    ->column('Priority')
                    ->column('Status')
                    ->column('Owner'),
            ),
            BulkActions::make(
                ToolbarAction::make('Mark done')->icon('check'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('2 tasks selected'),
            DataTable::make()
                ->headers(['Task', 'Related', 'Due', 'Priority', 'Status', 'Owner', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('inbox')
                        ->title('No tasks')
                        ->description('Create follow-ups tied to deals and leads.')
                        ->action(ToolbarAction::make('New task')->icon('plus')->primary()->href('#')),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Tasks')->current(),
            ))
            ->title('Tasks')
            ->description('Follow-ups and to-dos for the sales team.')
            ->actions(ToolbarAction::make('New task')->icon('plus')->primary()->href('#'));

        return PanelLayout::wrap('tasks', $page);
    }
}
