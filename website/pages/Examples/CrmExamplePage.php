<?php

declare(strict_types=1);

namespace App\Examples;

use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class CrmExamplePage extends ExamplePage
{
    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        unset($query);

        return self::showcase(
            'examples-crm',
            'CRM Showcase',
            'Customers, companies, and pipeline UI sketches.',
            [
                self::metric('Open deals', '37'),
                Card::make()
                    ->title('Top customers')
                    ->child(
                        Column::make([
                            Text::make('Acme Corp — $12,400')->size('sm'),
                            Text::make('Globex — $9,100')->size('sm')->tone('muted'),
                        ])->gap(2),
                    ),
            ],
        );
    }
}
