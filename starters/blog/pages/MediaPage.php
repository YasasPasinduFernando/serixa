<?php

declare(strict_types=1);

namespace App;

use App\Data\Fixtures;
use Serixa\Admin\Breadcrumb\Breadcrumb;
use Serixa\Admin\Breadcrumb\BreadcrumbItem;
use Serixa\Admin\Cards\InfoCard;
use Serixa\Admin\Page\Page;
use Serixa\Admin\Toolbar\ToolbarAction;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Icons\Component\Icon;

/**
 * Media library: a responsive grid of uploaded files (fake data only).
 */
final class MediaPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        $cards = [];

        foreach (Fixtures::media() as $item) {
            $cards[] = self::card($item);
        }

        $page = Page::make(
            Row::make($cards)->gap(4)->class('flex-wrap'),
        )
            ->breadcrumb(Breadcrumb::make(
                BreadcrumbItem::make('Dashboard')->href('?page=dashboard'),
                BreadcrumbItem::make('Media')->current(),
            ))
            ->title('Media')
            ->description('Images and files uploaded from the post editor.')
            ->actions(ToolbarAction::make('Upload')->icon('cloud-arrow-up')->primary()->href('#'));

        return BlogLayout::wrap('media', $page);
    }

    /**
     * @param array{filename: string, type: string, size: string, uploadedBy: string, uploadedAt: string} $item
     */
    private static function card(array $item): WidgetInterface
    {
        return InfoCard::make(
            Column::make(
                Icon::make(self::iconFor($item['type']))->size('xl')->color('primary'),
                $item['filename'],
                sprintf('%s · %s', $item['size'], $item['uploadedBy']),
                sprintf('Uploaded %s', $item['uploadedAt']),
            )->gap(1),
        )->class('w-64');
    }

    private static function iconFor(string $type): string
    {
        return match ($type) {
            'image' => 'photo',
            'video' => 'film',
            default => 'document',
        };
    }
}
