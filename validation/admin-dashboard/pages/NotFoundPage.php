<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Document\Document;

final class NotFoundPage
{
    public static function document(): Document
    {
        $content = EmptyState::make()
            ->title('Page not found')
            ->description('That admin route does not exist.')
            ->action(Link::make('Go to dashboard')->href('/?page=dashboard'));

        return Document::make()
            ->title('Not found · Admin')
            ->body(AdminLayout::shell('notfound', $content));
    }
}
