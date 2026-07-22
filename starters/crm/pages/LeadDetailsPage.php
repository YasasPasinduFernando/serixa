<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Components\DescriptionItem;
use Serixa\Admin\Components\DescriptionList;
use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

final class LeadDetailsPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $id = isset($query['id']) ? (int) $query['id'] : 0;
        $lead = Fixtures::lead($id);

        if ($lead === null) {
            $page = Page::make(
                EmptyState::make()
                    ->icon('inbox')
                    ->title('Lead not found')
                    ->description('That lead id is not in the demo fixtures.')
                    ->action(ToolbarAction::make('Back to leads')->icon('home')->primary()->href('?page=leads')),
            )
                ->breadcrumb(Breadcrumb::make(
                    BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                    BreadcrumbItem::make('Leads')->href('?page=leads'),
                    BreadcrumbItem::make('Not found')->current(),
                ))
                ->title('Lead not found');

            return PanelLayout::wrap('lead', $page);
        }

        $activityRows = [];
        foreach (array_slice(Fixtures::activityFeed(), 0, 4) as $item) {
            $activityRows[] = [
                $item['actor'],
                $item['summary'],
                $item['at'],
            ];
        }

        $page = Page::make(
            InfoCard::make(
                DescriptionList::make(
                    DescriptionItem::make('Name', $lead['name']),
                    DescriptionItem::make('Company', $lead['company']),
                    DescriptionItem::make('Source', $lead['source']),
                    DescriptionItem::make('Status', Presenter::leadStatusBadge($lead['status'])),
                    DescriptionItem::make('Score', (string) $lead['score']),
                    DescriptionItem::make('Owner', $lead['owner']),
                    DescriptionItem::make('Value', $lead['value']),
                    DescriptionItem::make('Updated', $lead['updated']),
                ),
            )->title('Lead details'),
            InfoCard::make(
                DataTable::make()
                    ->headers(['Actor', 'Activity', 'When'])
                    ->rows($activityRows)
                    ->hover(),
            )->title('Recent activity'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Leads')->href('?page=leads'),
                BreadcrumbItem::make($lead['name'])->current(),
            ))
            ->title($lead['name'])
            ->description($lead['company'] . ' · ' . $lead['source'])
            ->actions(
                ToolbarAction::make('Edit')->icon('pencil')->href('#'),
                ToolbarAction::make('Back')->icon('home')->href('?page=leads'),
            );

        return PanelLayout::wrap('lead', $page);
    }
}
