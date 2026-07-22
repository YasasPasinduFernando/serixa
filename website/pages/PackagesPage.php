<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Link;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Official packages index.
 */
final class PackagesPage
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

        $cards = [];
        foreach (PackageCatalog::all() as $package) {
            $cards[] = Card::make()
                ->title($package['title'])
                ->child(Text::make($package['name'])->size('sm')->tone('muted'))
                ->child(Text::make($package['description']))
                ->child(Link::make('Documentation →')->href($package['href']));
        }

        $page = Container::make(
            Column::make([
                Text::make('Official Packages')->as('h1')->size('2xl')->weight('bold'),
                Text::make('Every first-party SERIXA package in the ecosystem.')->tone('muted'),
                Column::make($cards)->gap(4),
            ])->gap(5)->alignStart(),
        )->maxWidth('xl')->padding('lg');

        return SiteLayout::wrap('packages', $page, 'Packages');
    }
}
