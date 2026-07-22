<?php

declare(strict_types=1);

namespace App\Examples;

use App\SiteLayout;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Link;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;

/**
 * Shared helpers for example showcase pages.
 */
abstract class ExamplePage
{
    /**
     * @param list<WidgetInterface> $sections
     */
    protected static function showcase(
        string $route,
        string $title,
        string $summary,
        array $sections,
    ): Document {
        $nav = Row::make([
            Link::make('Blog')->href('?page=examples-blog')->active($route === 'examples-blog'),
            Link::make('Admin')->href('?page=examples-admin')->active($route === 'examples-admin'),
            Link::make('CRM')->href('?page=examples-crm')->active($route === 'examples-crm'),
            Link::make('Ecommerce')->href('?page=examples-ecommerce')->active($route === 'examples-ecommerce'),
            Link::make('Portfolio')->href('?page=examples-portfolio')->active($route === 'examples-portfolio'),
        ])->gap(3)->class('example-nav');

        $page = Container::make(
            Column::make([
                $nav,
                Text::make($title)->as('h1')->size('2xl')->weight('bold'),
                Text::make($summary)->tone('muted'),
                Column::make($sections)->gap(4),
            ])->gap(5)->alignStart(),
        )->maxWidth('xl')->padding('lg');

        return SiteLayout::wrap($route, $page, $title);
    }

    protected static function metric(string $label, string $value): Card
    {
        return Card::make()
            ->title($label)
            ->child(Text::make($value)->as('p')->size('xl')->weight('bold'));
    }
}
