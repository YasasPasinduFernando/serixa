<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Link;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

/**
 * Breadcrumb trail for documentation pages.
 */
final class Breadcrumbs
{
    private function __construct()
    {
    }

    /**
     * @param list<array{label: string, href?: string|null}> $crumbs
     */
    public static function make(array $crumbs): WidgetInterface
    {
        $nodes = [];

        foreach ($crumbs as $i => $crumb) {
            if ($i > 0) {
                $nodes[] = Text::make('/')->as('span')->size('sm')->tone('muted');
            }

            $href = $crumb['href'] ?? null;
            if (is_string($href) && $href !== '') {
                $nodes[] = Link::make($crumb['label'])->href($href)->class('breadcrumb__link');
            } else {
                $nodes[] = Text::make($crumb['label'])->as('span')->size('sm')->weight('medium');
            }
        }

        return Row::make($nodes)->gap(2)->alignCenter()->class('breadcrumbs');
    }
}
