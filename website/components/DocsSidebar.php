<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Column;
use Serixa\Component\Component;
use Serixa\Component\Link;
use Serixa\Component\Text;

/**
 * Left documentation sidebar grouped by section.
 */
final class DocsSidebar
{
    private function __construct()
    {
    }

    public static function make(string $activeSlug): Component
    {
        $groups = [];

        foreach (DocsCatalog::groups() as $group) {
            $items = [];
            foreach (DocsCatalog::group($group) as $entry) {
                $active = $entry['slug'] === $activeSlug;
                $items[] = Link::make($entry['title'])
                    ->href(DocsCatalog::href($entry['slug']))
                    ->active($active)
                    ->class('docs-sidebar__link');
            }

            $groups[] = Column::make([
                Text::make($group)->as('p')->size('xs')->weight('semibold')->class('docs-sidebar__group'),
                Column::make($items)->gap(1),
            ])->gap(2)->alignStart();
        }

        return Column::make([
            Text::make('Documentation')->as('p')->size('sm')->weight('bold'),
            ...$groups,
        ])->gap(5)->alignStart()->class('docs-sidebar');
    }
}
