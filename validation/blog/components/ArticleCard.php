<?php

declare(strict_types=1);

namespace App\Components;

use Serixa\Component\Badge;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Image;
use Serixa\Component\Link;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;

final class ArticleCard
{
    public static function make(
        string $title,
        string $excerpt,
        string $image,
        string $category,
        string $href = '/?page=article',
    ): WidgetInterface {
        return Card::make(
            Column::make([
                Image::make()->src($image)->alt($title)->width(960)->height(540),
                Badge::make($category)->color('primary'),
                Text::make($title)->as('h2')->size('xl')->weight('bold'),
                Text::make($excerpt)->tone('muted'),
                Link::make('Read article')->href($href),
            ])->gap(3)->alignStart(),
        )->padding('lg');
    }
}
