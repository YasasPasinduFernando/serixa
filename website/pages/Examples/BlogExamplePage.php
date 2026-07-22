<?php

declare(strict_types=1);

namespace App\Examples;

use Serixa\Component\Badge;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class BlogExamplePage extends ExamplePage
{
    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        unset($query);

        return self::showcase(
            'examples-blog',
            'Blog Showcase',
            'Content-focused layout inspired by the official blog starter.',
            [
                Card::make()
                    ->title('Latest posts')
                    ->child(
                        Column::make([
                            Text::make('Shipping SERIXA v1.0 RC')->weight('semibold'),
                            Text::make('Release candidate notes for the ecosystem.')->tone('muted')->size('sm'),
                            Badge::make('Published'),
                        ])->gap(2),
                    ),
                Card::make()
                    ->title('Categories')
                    ->child(Text::make('Guides · Releases · Architecture')->tone('muted')),
                self::metric('Posts', '128'),
            ],
        );
    }
}
