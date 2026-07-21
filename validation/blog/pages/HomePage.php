<?php

declare(strict_types=1);

namespace App\Pages;

use App\Components\ArticleCard;
use App\Layouts\SiteLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class HomePage
{
    public static function document(): Document
    {
        $content = Column::make([
            Column::make([
                Text::make('Ideas for building calmer, better software')
                    ->as('h1')->size('2xl')->weight('bold'),
                Text::make('Practical essays about PHP, product craft, accessibility, and the people behind the code.')
                    ->size('lg')->tone('muted'),
                Row::make([
                    Button::make('Explore articles')->large(),
                    Button::make('Meet the authors')->secondary()->large(),
                ])->gap(3),
            ])->gap(4)->alignStart(),
            Text::make('Featured stories')->as('h2')->size('xl')->weight('bold'),
            Row::make([
                ArticleCard::make(
                    'Server-rendered interfaces that feel alive',
                    'How progressive enhancement keeps resilient PHP applications fast and accessible.',
                    'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=960&q=80',
                    'Engineering',
                ),
                ArticleCard::make(
                    'Design systems for small teams',
                    'A lightweight approach to shared language, reusable patterns, and consistent products.',
                    'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=960&q=80',
                    'Design',
                ),
            ])->gap(5)->alignStart(),
        ])->gap(6);

        return Document::make()
            ->title('SERIXA Journal')
            ->description('Essays about thoughtful software and product craft.')
            ->language('en')
            ->body(SiteLayout::shell('home', $content));
    }
}
