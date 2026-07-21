<?php

declare(strict_types=1);

namespace SerixaDemo\Components;

use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Contracts\WidgetInterface;

/**
 * Consistent page intro: PageHeader stacked with body children.
 */
final class PageSection
{
    /**
     * @param list<WidgetInterface|string> $actions
     */
    public static function make(
        string $title,
        ?string $subtitle = null,
        array $actions = [],
        WidgetInterface ...$children,
    ): WidgetInterface {
        $header = PageHeader::make()->title($title);

        if ($subtitle !== null) {
            $header->subtitle($subtitle);
        }

        if ($actions !== []) {
            $header->actions(...$actions);
        }

        return Column::make([$header, ...$children])->gap(2)->alignStart();
    }
}
