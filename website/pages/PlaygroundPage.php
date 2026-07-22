<?php

declare(strict_types=1);

namespace App;

use Serixa\Calendar\Component\MiniCalendar;
use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Charts\Component\BarChart;
use Serixa\Charts\Dataset\Dataset;
use Serixa\Charts\Dataset\Series;
use Serixa\Charts\Theme\ChartsTheme;
use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\Row;
use Serixa\Component\Tab;
use Serixa\Component\Tabs;
use Serixa\Component\Text;
use Serixa\Document\Document;
use Serixa\Icons\Component\Icon;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

/**
 * Interactive widget showcase — SERIXA widgets only.
 */
final class PlaygroundPage
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

        ThemeManager::set(new CalendarTheme(new ChartsTheme(new DefaultTheme())));

        $widgets = Card::make()
            ->title('Widgets')
            ->child(
                Row::make([
                    Button::make('Primary')->primary(),
                    Button::make('Secondary')->secondary(),
                    Button::make('Ghost')->ghost(),
                    Icon::make('home')->size('lg')->color('primary'),
                ])->gap(3)->alignCenter(),
            );

        $forms = Card::make()
            ->title('Forms')
            ->child(
                Form::make()->method('GET')->action('?page=playground')->child(
                    Column::make([
                        Label::make('Email')->for('pg-email'),
                        Input::make()->id('pg-email')->name('email')->type('email')->placeholder('you@example.com'),
                        Button::make('Submit')->primary()->type('submit'),
                    ])->gap(2),
                ),
            );

        $charts = Card::make()
            ->title('Charts')
            ->child(
                BarChart::make()
                    ->labels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri'])
                    ->dataset(
                        Dataset::make()->addSeries(Series::make('Visits')->values([12, 19, 8, 15, 22])),
                    )
                    ->legend(),
            );

        $calendar = Card::make()
            ->title('Calendar')
            ->child(MiniCalendar::make()->selected('2026-07-22'));

        $tabs = Card::make()
            ->title('Interactive')
            ->child(
                Tabs::make()
                    ->tab(Tab::make('Overview')->content(Text::make('Overview panel content.')->tone('muted'))->active())
                    ->tab(Tab::make('Details')->content(Text::make('Details panel content.')->tone('muted'))),
            );

        $page = Container::make(
            Column::make([
                Text::make('Playground')->as('h1')->size('2xl')->weight('bold'),
                Text::make('Live SERIXA widgets — framework, icons, forms, charts, and calendar.')->tone('muted'),
                $widgets,
                $forms,
                $charts,
                $calendar,
                $tabs,
            ])->gap(6)->alignStart(),
        )->maxWidth('xl')->padding('lg');

        return SiteLayout::wrap('playground', $page, 'Playground');
    }
}
