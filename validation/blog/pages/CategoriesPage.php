<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\SiteLayout;
use Serixa\Component\Badge;
use Serixa\Component\Chip;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class CategoriesPage
{
    public static function document(): Document
    {
        $categories = [
            ['Engineering', '12', 'primary'],
            ['Design', '8', 'success'],
            ['Product', '6', 'warning'],
            ['Accessibility', '5', 'primary'],
            ['Writing', '4', 'neutral'],
            ['Community', '3', 'success'],
        ];

        $rows = [];
        foreach ($categories as [$name, $count, $color]) {
            $rows[] = Row::make([
                Chip::make($name)->color($color),
                Badge::make($count . ' articles')->color($color),
            ])->gap(2)->alignCenter();
        }

        return Document::make()
            ->title('Categories · SERIXA Journal')
            ->body(SiteLayout::shell('categories', Column::make([
                PageHeader::make()->title('Categories')->subtitle('Follow the subjects that interest you.'),
                Text::make('Browse our growing library by topic.')->tone('muted'),
                Row::make($rows)->gap(5)->alignStart(),
            ])->gap(5)));
    }
}
