<?php

declare(strict_types=1);

namespace App\Examples;

use Serixa\Component\Card;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class EcommerceExamplePage extends ExamplePage
{
    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        unset($query);

        return self::showcase(
            'examples-ecommerce',
            'Ecommerce Showcase',
            'Catalog and storefront shell patterns.',
            [
                Row::make([
                    Card::make()->title('Linen Tee')->child(Text::make('$48')->weight('bold')),
                    Card::make()->title('Canvas Tote')->child(Text::make('$32')->weight('bold')),
                    Card::make()->title('Desk Lamp')->child(Text::make('$89')->weight('bold')),
                ])->gap(4),
                self::metric('Cart items', '3'),
            ],
        );
    }
}
