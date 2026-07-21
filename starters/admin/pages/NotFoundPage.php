<?php

declare(strict_types=1);

namespace App;

use Serixa\Admin\EmptyState\EmptyState;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Document\Document;

/**
 * Fallback page for unknown `?page=` values. {@see Web::isKnown()} is used
 * by the front controller to also send a real 404 status code.
 */
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
        $page = Page::make(
            EmptyState::make()
                ->icon('exclamation-triangle')
                ->title('Page not found')
                ->description('The page you are looking for does not exist or may have been moved.')
                ->action(ToolbarAction::make('Back to dashboard')->icon('home')->primary()->href('?page=dashboard')),
        )->title('Not found');

        return PanelLayout::wrap('not-found', $page);
    }
}
