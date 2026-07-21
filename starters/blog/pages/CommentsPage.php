<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Tables\RowActions;
use Serixa\Admin\Tables\SearchToolbar;
use Serixa\Admin\Tables\TableFilters;
use Serixa\Component\DataTable;
use Serixa\Component\Select;
use Serixa\Document\Document;

/**
 * Comment moderation queue across every post.
 */
final class CommentsPage
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

        foreach (Fixtures::comments() as $comment) {
            $rows[] = [
                $comment['author'],
                mb_strimwidth($comment['body'], 0, 80, '…'),
                $comment['postTitle'],
                Presenter::commentStatusBadge($comment['status']),
                $comment['createdAt'],
                RowActions::make()->link('Approve', '#')->link('Delete', '#'),
            ];
        }

        $table = DataTable::make()
            ->headers(['Author', 'Comment', 'Post', 'Status', 'Date', ''])
            ->rows($rows)
            ->hover();

        $page = Page::make(
            SearchToolbar::make()->placeholder('Search comments…'),
            TableFilters::make(
                Select::make()->name('status')->placeholder('All statuses')->options([
                    'approved' => 'Approved',
                    'pending' => 'Pending',
                    'spam' => 'Spam',
                ]),
            ),
            $table,
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Comments')->current(),
            ))
            ->title('Comments')
            ->description('Moderate reader feedback across every post.');

        return BlogLayout::wrap('comments', $page);
    }
}
