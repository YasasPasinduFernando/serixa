<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Badge;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\Pagination;
use Serixa\Component\SearchField;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;

final class ArticlesPage
{
    public static function document(): Document
    {
        $table = DataTable::make()
            ->headers(['Article', 'Category', 'Published'])
            ->rows([
                [Link::make('Server-rendered interfaces that feel alive')->href('/?page=article'), Badge::make('Engineering')->color('primary'), 'July 18, 2026'],
                [Link::make('Design systems for small teams')->href('/?page=article'), Badge::make('Design')->color('success'), 'July 12, 2026'],
                [Link::make('Writing documentation people can use')->href('/?page=article'), Badge::make('Writing')->color('warning'), 'July 5, 2026'],
                [Link::make('A humane approach to performance')->href('/?page=article'), Badge::make('Product')->color('neutral'), 'June 29, 2026'],
            ])
            ->caption('Latest SERIXA Journal articles')
            ->striped();

        return Document::make()
            ->title('Articles · SERIXA Journal')
            ->description('Browse the latest SERIXA Journal articles.')
            ->body(SiteLayout::shell('articles', Column::make([
                PageHeader::make()->title('Articles')->subtitle('Notes on building useful, durable products.'),
                Toolbar::make()
                    ->leading(SearchField::make()->name('q')->placeholder('Search articles'))
                    ->trailing(Badge::make('24 stories')->color('primary')),
                $table,
                Pagination::make()
                    ->currentPage(1)
                    ->totalPages(4)
                    ->urlForPage(static fn (int $page): string => '/?page=articles&p=' . $page),
            ])->gap(5)));
    }
}
