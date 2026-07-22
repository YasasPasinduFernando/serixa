<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Link;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class NotFoundPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        unset($query);

        $page = Container::make(
            Column::make([
                Text::make('Page not found')->as('h1')->size('2xl')->weight('bold'),
                Text::make('That documentation route does not exist.')->tone('muted'),
                Link::make('Back to home')->href('?page=home'),
            ])->gap(3),
        )->maxWidth('md')->padding('lg');

        return SiteLayout::wrap('not-found', $page, 'Not Found');
    }
}
