<?php

declare(strict_types=1);

namespace App\Examples;

use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class AdminExamplePage extends ExamplePage
{
    /**
     * @param array<string, mixed> $query
     */
    public static function document(array $query = []): Document
    {
        unset($query);

        return self::showcase(
            'examples-admin',
            'Admin Showcase',
            'Dashboard shell patterns from the official admin starter.',
            [
                Row::make([
                    self::metric('Users', '2,481'),
                    self::metric('Orders', '914'),
                    self::metric('Revenue', '$48k'),
                ])->gap(4),
                Card::make()
                    ->title('Recent activity')
                    ->child(
                        Column::make([
                            Text::make('Ada created a role')->size('sm'),
                            Text::make('Grace updated permissions')->size('sm')->tone('muted'),
                        ])->gap(2),
                    ),
            ],
        );
    }
}
