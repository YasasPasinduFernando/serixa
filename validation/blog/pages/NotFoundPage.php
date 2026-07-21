<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Document\Document;

final class NotFoundPage
{
    public static function document(): Document
    {
        $state = EmptyState::make()
            ->icon('404')
            ->title('Page not found')
            ->description('The page may have moved, or the address may be incorrect.')
            ->action(Link::make('Return home')->href('/?page=home'));

        return Document::make()
            ->title('Page not found · SERIXA Journal')
            ->robots('noindex')
            ->body(SiteLayout::shell('', $state));
    }
}
