<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

/**
 * Category index: name, slug, description and post count.
 */
final class CategoriesPage
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

        foreach (Fixtures::categories() as $category) {
            $rows[] = [
                $category['name'],
                $category['slug'],
                $category['description'],
                (string) $category['postCount'],
                RowActions::make()->link('Edit', '#')->link('Delete', '#'),
            ];
        }

        $table = DataTable::make()
            ->headers(['Name', 'Slug', 'Description', 'Posts', ''])
            ->rows($rows)
            ->hover();

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search categories…'),
            $table,
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Categories')->current(),
            ))
            ->title('Categories')
            ->description('Organize posts into topical groups.')
            ->actions(ToolbarAction::make('New category')->icon('plus')->primary()->href('#'));

        return BlogLayout::wrap('categories', $page);
    }
}
