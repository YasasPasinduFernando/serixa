<?php

declare(strict_types=1);

namespace App\Examples;

use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class PortfolioExamplePage extends ExamplePage
{
    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        unset($query);

        return self::showcase(
            'examples-portfolio',
            'Portfolio Showcase',
            'Project grid and about sections for personal sites.',
            [
                Card::make()
                    ->title('Selected work')
                    ->child(
                        Column::make([
                            Text::make('Northwind redesign')->weight('semibold'),
                            Text::make('Brand system and marketing site.')->tone('muted')->size('sm'),
                        ])->gap(2),
                    ),
                Card::make()
                    ->title('About')
                    ->child(Text::make('Designer & engineer building calm interfaces.')->tone('muted')),
            ],
        );
    }
}
