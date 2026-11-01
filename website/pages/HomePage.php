<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Alert;
use Serixa\Component\Avatar;
use Serixa\Component\Badge;
use Serixa\Component\Card;
use Serixa\Component\Chip;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Image;
use Serixa\Component\Link;
use Serixa\Component\Progress;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Official documentation homepage — built entirely from SERIXA widgets.
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

        $heroCopy = Column::make([
            Badge::make('Stable v1.0.2 · Composer ready')->color('success')->class('hero-eyebrow'),
            Text::make('Build expressive interfaces. Keep the web simple.')
                ->as('h1')
                ->size('2xl')
                ->weight('bold')
                ->class('hero-title'),
            Text::make('SERIXA brings a Flutter-inspired widget tree to PHP, then renders clean semantic HTML with an offline theme compiler and a tiny progressive-enhancement runtime.')
                ->tone('muted')
                ->class('hero-lead'),
            Row::make([
                Link::make('Start building')->href(DocsCatalog::href('installation'))->class('btn-primary hero-primary'),
                Link::make('Explore the architecture')
                    ->href(DocsCatalog::href('rendering'))
                    ->class('btn-secondary'),
            ])->gap(3)->alignCenter()->class('hero-actions'),
            Row::make([
                self::signal('PHP 8.2+', 'Modern runtime'),
                self::signal('Semantic HTML', 'Accessible output'),
                self::signal('No hydration', 'Lean by design'),
            ])->gap(3)->class('hero-signals'),
        ])->gap(5)->alignStart()->class('hero-copy');

        $heroVisual = Column::make([
            Image::make()
                ->src('/assets/brand/logo-full-1200w.png')
                ->alt('SERIXA PHP UI Framework')
                ->width(1200)
                ->height(973)
                ->loading('eager')
                ->class('hero-logo'),
            Column::make([
                Row::make([
                    Text::make('Widget tree')->size('sm')->weight('semibold'),
                    Badge::make('PHP')->color('primary'),
                ])->spaceBetween()->alignCenter(),
                Text::make('Document → Layout → Components')->tone('muted')->size('sm'),
                Row::make([
                    self::node('Text'),
                    self::node('Button'),
                    self::node('Card'),
                ])->gap(2)->class('hero-nodes'),
                Row::make([
                    Text::make('Semantic HTML')->size('sm')->weight('semibold'),
                    Text::make('SSR · 0 hydration')->size('sm')->tone('muted'),
                ])->spaceBetween()->class('hero-output'),
            ])->gap(3)->class('hero-inspector'),
        ])->gap(2)->alignCenter()->class('hero-visual');

        $hero = Row::make([$heroCopy, $heroVisual])
            ->spaceBetween()
            ->alignCenter()
            ->class('hero hero-grid');

        $principles = Column::make([
            self::sectionHeading(
                'A UI system that still feels like PHP',
                'Compose layouts with familiar server-side code. SERIXA handles rendering, themes and progressive behavior without turning your application into a JavaScript runtime.',
            ),
            Row::make([
                self::feature('01', 'Compose', 'Build interfaces from typed widgets, Rows, Columns and reusable components.'),
                self::feature('02', 'Compile', 'Generate theme-aware CSS offline from tokens. No browser compiler and no CDN dependency.'),
                self::feature('03', 'Render', 'Ship semantic server-rendered HTML with accessibility contracts and predictable output.'),
            ])->gap(4)->class('feature-row feature-grid'),
        ])->gap(6)->class('section principles-section');

        $code = Column::make([
            Row::make([
                Text::make('A complete interface in PHP')->as('h2')->size('xl')->weight('bold'),
                Badge::make('SERIXA syntax')->color('primary'),
            ])->spaceBetween()->alignCenter()->class('showcase-heading'),
            Text::make('The widget tree stays readable, testable and close to the HTML it produces.')->tone('muted'),
            CodeSample::make(<<<'PHP'
use Serixa\Component\{Button, Card, Column, Text};
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$profile = Card::make(
    Column::make([
        Text::make('Welcome back')->as('h1')->size('2xl'),
        Text::make('Your workspace is ready.')->tone('muted'),
        Button::make('Open dashboard')->primary(),
    ])->gap(4)
);

echo (new Renderer())->render(
    Document::make()->title('Dashboard')->body($profile)
);
PHP),
            Row::make([
                self::metric('Widget tree', 'Declarative composition'),
                self::metric('Theme tokens', 'Consistent by default'),
                self::metric('HTML output', 'Search and screen-reader friendly'),
            ])->gap(3)->class('code-metrics'),
        ])->gap(4)->class('section code-showcase');

        $components = Column::make([
            self::sectionHeading(
                'A component system you can see working',
                'This page is not a visual mockup. Every surface below is rendered by the same composable SERIXA components you can import into your own PHP application.',
            ),
            Row::make([
                Column::make([
                    Row::make([
                        Text::make('Release workspace')->weight('bold'),
                        Badge::make('Live')->color('success'),
                    ])->spaceBetween()->alignCenter(),
                    Text::make('A small interface composed from real framework primitives.')->size('sm')->tone('muted'),
                    Alert::make()
                        ->tone('success')
                        ->title('Ready to ship')
                        ->body('Theme tokens compiled and accessibility contracts passed.'),
                    Column::make([
                        Row::make([
                            Text::make('Build progress')->size('sm')->weight('semibold'),
                            Text::make('84%')->size('sm')->tone('muted'),
                        ])->spaceBetween(),
                        Progress::make()->value(84)->label('Build progress'),
                    ])->gap(2),
                    Row::make([
                        Chip::make('PHP 8.2')->color('primary'),
                        Chip::make('SSR')->color('success'),
                        Chip::make('Semantic HTML'),
                    ])->gap(2)->class('component-chips'),
                ])->gap(4)->class('component-preview'),
                Column::make([
                    Badge::make('Copy the pattern')->color('primary'),
                    Text::make('Import only what the interface needs.')->as('h3')->size('xl')->weight('bold'),
                    Text::make('Typed APIs keep composition discoverable while the renderer keeps output predictable.')
                        ->tone('muted'),
                    CodeSample::make(<<<'PHP'
use Serixa\Component\{
    Alert, Badge, Card,
    Chip, Progress, Row, Text
};

$status = Card::make(
    Alert::make()
        ->tone('success')
        ->title('Ready to ship')
        ->body('All checks passed.')
);
PHP),
                ])->gap(3)->alignStart()->class('component-code'),
            ])->gap(5)->class('component-stage'),
            Row::make([
                Row::make([
                    Avatar::make()->initials('SX')->size('lg'),
                    Column::make([
                        Text::make('Built with SERIXA')->weight('bold'),
                        Text::make('The official docs are a production proof of the framework.')->size('sm')->tone('muted'),
                    ])->gap(1),
                ])->gap(3)->alignCenter(),
                Link::make('Explore every component →')->href('?page=components')->class('text-link'),
            ])->spaceBetween()->alignCenter()->class('built-with-panel'),
        ])->gap(5)->class('section components-section');

        $packages = Column::make([
            self::sectionHeading(
                'One foundation, an expanding ecosystem',
                'Start with the framework, then add focused packages when the product needs them.',
            ),
            Row::make(array_map(
                static fn (array $package): Card => Card::make()
                    ->title($package['title'])
                    ->child(Text::make($package['name'])->size('sm')->tone('muted')->class('package-name'))
                    ->child(Text::make($package['description'])->tone('muted'))
                    ->child(Link::make('Explore package ↗')->href($package['href'])->class('card-link'))
                    ->class('package-card'),
                PackageCatalog::featured(6),
            ))->gap(4)->class('package-grid'),
            Link::make('Browse the full package catalog →')->href('?page=packages')->class('text-link'),
        ])->gap(5)->class('section ecosystem-section');

        $starters = Column::make([
            self::sectionHeading(
                'Go from idea to a real product',
                'Use a starter as a learning reference or the first commit of your next PHP interface.',
            ),
            Row::make([
                self::starter('Blog', 'Editorial foundation', 'examples-blog'),
                self::starter('Admin', 'Operational dashboards', 'examples-admin'),
                self::starter('CRM', 'Customer workflows', 'examples-crm'),
                self::starter('Commerce', 'Product experiences', 'examples-ecommerce'),
                self::starter('Portfolio', 'Personal presence', 'examples-portfolio'),
            ])->gap(3)->class('starter-row starter-grid'),
        ])->gap(5)->class('section starters-section');

        $cta = Row::make([
            Column::make([
                Badge::make('Build the interface, not the machinery')->color('primary'),
                Text::make('Your next PHP UI can be this clear.')->as('h2')->size('2xl')->weight('bold'),
                Text::make('Install the stable framework, follow the guided introduction, and render your first widget tree today.')
                    ->tone('muted'),
            ])->gap(3)->alignStart(),
            Column::make([
                Link::make('Read the quick start')->href(DocsCatalog::href('quick-start'))->class('btn-primary'),
                Link::make('View source on GitHub')
                    ->href('https://github.com/YasasPasinduFernando/serixa')
                    ->class('btn-secondary'),
            ])->gap(3)->class('cta-actions'),
        ])->spaceBetween()->alignCenter()->class('section cta-panel');

        $page = Container::make(
            Column::make([$hero, $principles, $code, $components, $packages, $starters, $cta])
                ->gap(10)
                ->alignStretch(),
        )->maxWidth('xl')->padding('lg')->class('home-container');

        return SiteLayout::wrap('home', $page, 'Modern PHP UI Framework');
    }

    private static function sectionHeading(string $title, string $body): Column
    {
        return Column::make([
            Text::make($title)->as('h2')->size('2xl')->weight('bold')->class('section-title'),
            Text::make($body)->tone('muted')->class('section-lead'),
        ])->gap(2)->alignStart()->class('section-heading');
    }

    private static function feature(string $number, string $title, string $body): Card
    {
        return Card::make()
            ->child(Text::make($number)->size('sm')->weight('bold')->class('feature-number'))
            ->child(Text::make($title)->as('h3')->size('xl')->weight('bold'))
            ->child(Text::make($body)->tone('muted'))
            ->class('feature-card');
    }

    private static function starter(string $title, string $subtitle, string $page): Card
    {
        return Card::make()
            ->child(Text::make($title)->as('h3')->size('lg')->weight('bold'))
            ->child(Text::make($subtitle)->size('sm')->tone('muted'))
            ->child(Link::make('View starter →')->href('?page=' . $page)->class('card-link'))
            ->class('starter-card');
    }

    private static function signal(string $value, string $label): Column
    {
        return Column::make([
            Text::make($value)->size('sm')->weight('bold'),
            Text::make($label)->size('sm')->tone('muted'),
        ])->gap(1)->class('hero-signal');
    }

    private static function node(string $label): Text
    {
        return Text::make($label)->size('sm')->weight('semibold')->class('hero-node');
    }

    private static function metric(string $title, string $body): Column
    {
        return Column::make([
            Text::make($title)->size('sm')->weight('bold'),
            Text::make($body)->size('sm')->tone('muted'),
        ])->gap(1)->class('code-metric');
    }
}
