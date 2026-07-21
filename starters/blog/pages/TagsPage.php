<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\Chip;
use Serixa\Component\DataTable;
use Serixa\Component\Row;
use Serixa\Document\Document;

/**
 * Tag index: a chip cloud preview plus a full management table.
 */
final class TagsPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $tags = Fixtures::tags();

        $chips = [];

        foreach ($tags as $tag) {
            $chips[] = Chip::make(sprintf('%s · %d', $tag['name'], $tag['postCount']))->color('primary');
        }

        $rows = [];

        foreach ($tags as $tag) {
            $rows[] = [
                $tag['name'],
                $tag['slug'],
                (string) $tag['postCount'],
                RowActions::make()->link('Edit', '#')->link('Delete', '#'),
            ];
        }

        $table = DataTable::make()
            ->headers(['Tag', 'Slug', 'Posts', ''])
            ->rows($rows)
            ->hover();

        $page = Page::make(
            InfoCard::make(Row::make($chips)->gap(2)->class('flex-wrap'))->title('All tags'),
            $table,
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Tags')->current(),
            ))
            ->title('Tags')
            ->description('Fine-grained labels readers use to browse related posts.')
            ->actions(ToolbarAction::make('New tag')->icon('plus')->primary()->href('#'));

        return BlogLayout::wrap('tags', $page);
    }
}
