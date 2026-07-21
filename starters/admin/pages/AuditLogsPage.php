<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\ColumnVisibility;
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

final class AuditLogsPage
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

        foreach (Fixtures::auditLogs() as $entry) {
            $rows[] = [
                $entry['at'],
                $entry['actor'],
                $entry['action'],
                $entry['target'],
                Presenter::auditSeverityBadge($entry['severity']),
                $entry['ip'],
                RowActions::make()->link('Details', '#'),
            ];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search audit events…')
                ->actions(ExportButton::make()),
            TableFilters::make(
                Select::make()->name('severity')->placeholder('All severities')->options([
                    'info' => 'Info',
                    'warning' => 'Warning',
                    'critical' => 'Critical',
                ]),
                ColumnVisibility::make()
                    ->column('When')
                    ->column('Actor')
                    ->column('Action')
                    ->column('Target')
                    ->column('Severity')
                    ->column('IP'),
            ),
            DataTable::make()
                ->headers(['When', 'Actor', 'Action', 'Target', 'Severity', 'IP', ''])
                ->rows($rows)
                ->hover()
                ->emptyState(
                    EmptyState::make()
                        ->icon('clipboard-document-check')
                        ->title('No audit events')
                        ->description('Security-relevant actions will be recorded here.'),
                ),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Audit Logs')->current(),
            ))
            ->title('Audit Logs')
            ->description('Immutable trail of sensitive workspace actions.')
            ->actions(ToolbarAction::make('Export')->icon('arrow-path')->href('#'));

        return PanelLayout::wrap('audit-logs', $page);
    }
}
