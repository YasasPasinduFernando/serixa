<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use App\Support\Presenter;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Cards\MetricCard;
use Serixa\Admin\Cards\MetricGrid;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\Toolbar;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\DataTable;
use Serixa\Document\Document;

/**
 * Landing page: content KPIs, a jump-to toolbar, and the most recent posts.
 */
final class DashboardPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $posts = Fixtures::posts();
        $published = array_filter($posts, static fn (array $post): bool => $post['status'] === 'published');
        $drafts = array_filter($posts, static fn (array $post): bool => $post['status'] === 'draft');
        $pendingComments = array_filter(
            Fixtures::comments(),
            static fn (array $comment): bool => $comment['status'] === 'pending',
        );

        $recentRows = [];

        foreach (array_slice($posts, 0, 5) as $post) {
            $recentRows[] = [
                $post['title'],
                $post['category'],
                Presenter::postStatusBadge($post['status']),
                $post['author'],
                $post['publishedAt'],
            ];
        }

        $recentTable = DataTable::make()
            ->headers(['Title', 'Category', 'Status', 'Author', 'Published'])
            ->rows($recentRows)
            ->hover();

        $page = Page::make(
            MetricGrid::make(
                MetricCard::make()->title('Published posts')->value((string) count($published))->icon('document-text')->color('primary'),
                MetricCard::make()->title('Drafts')->value((string) count($drafts))->icon('pencil-square')->color('warning'),
                MetricCard::make()->title('Pending comments')->value((string) count($pendingComments))->icon('chat-bubble-left')->color('danger'),
                MetricCard::make()->title('Registered users')->value((string) count(Fixtures::users()))->icon('users')->color('success'),
            ),
            Toolbar::make(
                ToolbarAction::make('New post')->icon('plus')->primary()->href('?page=create-post'),
                ToolbarAction::make('View posts')->icon('document-text')->href('?page=posts'),
                ToolbarAction::make('View comments')->icon('chat-bubble-left')->href('?page=comments'),
            ),
            InfoCard::make($recentTable)->title('Recent posts'),
        )
            ->breadcrumb(Breadcrumb::make(BreadcrumbItem::make('Dashboard')->current()))
            ->title('Dashboard')
            ->description('An overview of your content, right now.');

        return BlogLayout::wrap('dashboard', $page);
    }
}
