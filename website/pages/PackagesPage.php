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
 * Official packages index — tiered catalog.
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

        $sections = [];
        foreach (PackageCatalog::grouped() as $tier => $packages) {
            if ($packages === []) {
                continue;
            }

            $cards = [];
            foreach ($packages as $package) {
                $badge = match ($package['maturity']) {
                    'stable' => 'Stable',
                    'experimental' => 'Experimental',
                    default => 'v0.1.0',
                };
                $cards[] = Card::make()
                    ->title($package['title'])
                    ->child(Text::make($package['name'] . ' · ' . $badge)->size('sm')->tone('muted'))
                    ->child(Text::make($package['description']))
                    ->child(Link::make('Documentation →')->href($package['href']));
            }

            $intro = match ($tier) {
                'Experimental Packages' => 'Client-port adapters with Fake* clients for offline QA. Inject real HTTP/SDK clients in production. Not battle-tested SaaS SDKs.',
                'Official Packages' => 'Domain packages at v0.1.0 — production-shaped APIs; continue to validate in your app before relying on them heavily.',
                default => '',
            };

            $block = [
                Text::make($tier)->as('h2')->size('xl')->weight('bold'),
            ];
            if ($intro !== '') {
                $block[] = Text::make($intro)->tone('muted');
            }
            $block[] = Column::make($cards)->gap(4);

            $sections[] = Column::make($block)->gap(3);
        }

        $page = Container::make(
            Column::make(array_merge([
                Text::make('Packages')->as('h1')->size('2xl')->weight('bold'),
                Text::make('First-party SERIXA packages in this monorepo. Tiers separate core UI, domain packages, and experimental adapters.')
                    ->tone('muted'),
            ], $sections))->gap(8)->alignStart(),
        )->maxWidth('xl')->padding('lg');

        return SiteLayout::wrap('packages', $page, 'Packages');
    }
}
