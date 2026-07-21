<?php

declare(strict_types=1);

namespace SerixaDemo\Pages;

use Serixa\Component\Button;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\SizedBox;
use Serixa\Component\Spacer;
use Serixa\Component\Text;
use Serixa\Document\Document;
use SerixaDemo\DocumentFactory;
use SerixaDemo\Missing;
use SerixaDemo\Shell;

final class CrudPage
{
    public static function document(): Document
    {
        $toolbar = Row::make([
            // Missing: SearchField / Input
            Missing::widget('SearchField', 'Search users by name or email'),
            // Missing: Select / FilterBar
            Missing::widget('Select', 'Filter by role / status'),
            Spacer::make(),
            Button::make('Create')->primary(),
        ])->alignCenter()->gap(3)->spaceBetween();

        $content = Column::make([
            Text::make('Users')->as('h1')->size('2xl')->weight('bold'),
            Text::make('CRUD table demo — widgets that do not exist are placeholders.')
                ->tone('muted')
                ->size('sm'),
            SizedBox::height(2),
            $toolbar,
            SizedBox::height(3),
            Card::make(
                // Missing: DataTable / Table
                Missing::widget('DataTable', 'Columns: Name, Email, Role, Actions'),
                Text::make('Ada Lovelace · ada@example.com · Admin')->size('sm'),
                Text::make('Alan Turing · alan@example.com · Editor')->size('sm')->tone('muted'),
                Text::make('Grace Hopper · grace@example.com · Viewer')->size('sm')->tone('muted'),
                SizedBox::height(2),
                Row::make([
                    Button::make('Edit')->secondary()->small(),
                    Button::make('Delete')->danger()->small(),
                ])->gap(2),
            )->title('Directory')->padding('md'),
            SizedBox::height(2),
            // Missing: Pagination
            Missing::widget('Pagination', 'Page controls (prev / next / page size)'),
        ])->gap(2)->alignStart();

        return DocumentFactory::page(
            'CRUD',
            Shell::app('crud', $content),
        );
    }
}
