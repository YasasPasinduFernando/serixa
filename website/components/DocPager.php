<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Link;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

/**
 * Previous / Next documentation navigation.
 */
final class DocPager
{
    private function __construct()
    {
    }

    public static function make(string $slug): WidgetInterface
    {
        $neighbors = DocsCatalog::neighbors($slug);
        $prev = $neighbors['previous'];
        $next = $neighbors['next'];

        $left = $prev !== null
            ? Link::make('← ' . $prev['title'])->href(DocsCatalog::href($prev['slug']))->class('doc-pager__link')
            : Text::make('')->as('span');

        $right = $next !== null
            ? Link::make($next['title'] . ' →')->href(DocsCatalog::href($next['slug']))->class('doc-pager__link')
            : Text::make('')->as('span');

        return Row::make([$left, $right])->spaceBetween()->alignCenter()->class('doc-pager');
    }
}
