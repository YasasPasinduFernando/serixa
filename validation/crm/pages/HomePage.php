<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Home page for the crm starter.
 */
final class HomePage
{
    public static function document(): Document
    {
        $body = Column::make([
            AppLayout::titleBar('Crm · crm'),
            Text::make('Welcome to your SERIXA crm application.')
                ->tone('muted'),
        ])->gap(3)->alignStart();

        return Document::make()
            ->title('Crm · crm')
            ->description('SERIXA crm starter')
            ->language('en')
            ->body($body);
    }
}
