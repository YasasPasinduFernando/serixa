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
use Serixa\Admin\Tables\ExportButton;
use Serixa\Admin\Tables\ImportButton;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

/**
 * Full post index: search, filters, bulk actions, and per-row actions.
 */
final class PostsPage
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

        foreach (Fixtures::posts() as $post) {
            $rows[] = [
                $post['title'],
                $post['category'],
                Presenter::postStatusBadge($post['status']),
                $post['author'],
                (string) $post['comments'],
                $post['publishedAt'],
                RowActions::make()
                    ->link('Edit', '?page=edit-post&id=' . $post['id'])
                    ->link('View', '#'),
            ];
        }

        $table = DataTable::make()
            ->headers(['Title', 'Category', 'Status', 'Author', 'Comments', 'Published', ''])
            ->rows($rows)
            ->hover()
            ->emptyState(
                EmptyState::make()
                    ->icon('document-text')
                    ->title('No posts yet')
                    ->description('Create your first post to see it listed here.')
                    ->action(ToolbarAction::make('New post')->icon('plus')->primary()->href('?page=create-post')),
            );

        $categoryOptions = [];

        foreach (Fixtures::categories() as $category) {
            $categoryOptions[$category['slug']] = $category['name'];
        }

        $page = Page::make(
            SearchToolbar::make()
                ->placeholder('Search posts by title or author…')
                ->actions(ImportButton::make(), ExportButton::make()),
            TableFilters::make(
                Select::make()->name('category')->placeholder('All categories')->options($categoryOptions),
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'published' => 'Published',
                    'draft' => 'Draft',
                    'scheduled' => 'Scheduled',
                ]),
                ColumnVisibility::make()
                    ->column('Title')
                    ->column('Category')
                    ->column('Status')
                    ->column('Author')
                    ->column('Comments', false)
                    ->column('Published'),
            ),
            BulkActions::make(
                ToolbarAction::make('Unpublish')->icon('eye-slash'),
                ToolbarAction::make('Delete')->icon('trash')->danger(),
            )->summary('2 posts selected'),
            $table,
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Posts')->current(),
            ))
            ->title('Posts')
            ->description('All posts across every author and category.')
            ->actions(ToolbarAction::make('New post')->icon('plus')->primary()->href('?page=create-post'));

        return BlogLayout::wrap('posts', $page);
    }
}
