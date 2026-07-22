<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Link;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Official documentation homepage.
 */
final class HomePage
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

        $hero = Column::make([
            Text::make('SERIXA')->as('h1')->size('2xl')->weight('bold')->class('hero-brand'),
            Text::make('Modern SSR UI Framework for PHP')->as('p')->size('xl')->weight('semibold'),
            Text::make('Write PHP widgets. Get semantic HTML. Theme with Tailwind. Ship with a tiny progressive-enhancement runtime.')
                ->tone('muted'),
            Row::make([
                Link::make('Get started')->href(DocsCatalog::href('installation'))->class('btn-primary'),
                Link::make('View on GitHub')
                    ->href('https://github.com/YasasPasinduFernando/serixa')
                    ->class('hero-github'),
            ])->gap(4)->alignCenter(),
        ])->gap(4)->alignStart()->class('hero');

        $features = Column::make([
            Text::make('Features')->as('h2')->size('xl')->weight('bold'),
            Row::make([
                self::feature('SSR widgets', 'Compose Flutter-inspired PHP widgets into clean HTML.'),
                self::feature('Theme seam', 'DefaultTheme maps variants to Tailwind — swap themes without touching widgets.'),
                self::feature('Tiny runtime', 'Optional serixa.js for modals, tabs, toasts — not a SPA framework.'),
            ])->gap(4)->class('feature-row'),
        ])->gap(4)->class('section');

        $install = Column::make([
            Text::make('Installation')->as('h2')->size('xl')->weight('bold'),
            Text::make('Install the framework with Composer, then scaffold with the CLI.')->tone('muted'),
            CodeSample::make("composer require serixa/framework\ncomposer require --dev serixa/cli\nvendor/bin/serixa new blog"),
            Link::make('Full installation guide →')->href(DocsCatalog::href('installation')),
        ])->gap(3)->class('section');

        $example = Column::make([
            Text::make('Code example')->as('h2')->size('xl')->weight('bold'),
            CodeSample::make(<<<'PHP'
use Serixa\Component\Button;
use Serixa\Component\Text;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$document = Document::make()
    ->title('Hello')
    ->body(
        Text::make('Hello, SERIXA')->as('h1')->size('xl')->weight('bold'),
        Button::make('Continue')->primary(),
    );

echo (new Renderer())->render($document);
PHP),
        ])->gap(3)->class('section');

        $packages = Column::make([
            Text::make('Official Packages')->as('h2')->size('xl')->weight('bold'),
            Column::make(array_map(
                static fn (array $p): Card => Card::make()
                    ->title($p['title'])
                    ->child(Text::make($p['name'])->size('sm')->tone('muted'))
                    ->child(Text::make($p['description'])->tone('muted'))
                    ->child(Link::make('Learn more →')->href($p['href'])),
                array_slice(PackageCatalog::all(), 0, 6),
            ))->gap(3),
            Link::make('All packages →')->href('?page=packages'),
        ])->gap(4)->class('section');

        $starters = Column::make([
            Text::make('Starter Kits')->as('h2')->size('xl')->weight('bold'),
            Row::make([
                self::starter('Blog', 'examples-blog'),
                self::starter('Admin', 'examples-admin'),
                self::starter('CRM', 'examples-crm'),
                self::starter('Ecommerce', 'examples-ecommerce'),
                self::starter('Portfolio', 'examples-portfolio'),
            ])->gap(3)->class('starter-row'),
        ])->gap(4)->class('section');

        $performance = Column::make([
            Text::make('Performance')->as('h2')->size('xl')->weight('bold'),
            Text::make('Server-rendered HTML with memoized theme lookups and a lean client runtime that mounts interactive modules only when markers are present.')
                ->tone('muted'),
            Link::make('Read the performance notes →')->href(DocsCatalog::href('rendering')),
        ])->gap(3)->class('section');

        $cta = Card::make()
            ->title('Build with SERIXA')
            ->child(Text::make('Star the repo, open an issue, or scaffold your first app.')->tone('muted'))
            ->child(
                Row::make([
                    Link::make('GitHub repository')->href('https://github.com/YasasPasinduFernando/serixa'),
                    Link::make('Installation')->href(DocsCatalog::href('installation')),
                ])->gap(4),
            )
            ->class('section cta-card');

        $page = Container::make(
            Column::make([
                $hero,
                $features,
                $install,
                $example,
                $packages,
                $starters,
                $performance,
                $cta,
            ])->gap(10)->alignStart(),
        )->maxWidth('xl')->padding('lg');

        return SiteLayout::wrap('home', $page, 'Documentation');
    }

    private static function feature(string $title, string $body): Card
    {
        return Card::make()
            ->title($title)
            ->child(Text::make($body)->tone('muted'))
            ->class('feature-card');
    }

    private static function starter(string $label, string $page): Card
    {
        return Card::make()
            ->title($label)
            ->child(Link::make('View showcase →')->href('?page=' . $page));
    }
}
