<?php

declare(strict_types=1);

namespace App\Pages;

use App\Components\CustomerCard;
use App\Layouts\CrmLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\Pagination;
use Serixa\Component\Row;
use Serixa\Component\SearchField;
use Serixa\Component\Text;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;

/**
 * Customers list with search, table, cards, and pagination.
 */
final class CustomersPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Customers')
                ->subtitle('Directory and accounts')
                ->actions(Button::make('Add customer')->primary()),
            Toolbar::make()
                ->leading(
                    SearchField::make()
                        ->name('q')
                        ->placeholder('Search customers…'),
                )
                ->trailing(Button::make('Export')->secondary()),
            DataTable::make()
                ->headers(['Name', 'Company', 'Status', 'Actions'])
                ->rows([
                    ['Ada Lovelace', 'Analytical Engines', 'Active', Link::make('Open')->href('/?page=customers&id=1')],
                    ['Alan Turing', 'Bombe Ltd', 'Lead', Link::make('Open')->href('/?page=customers&id=2')],
                    ['Grace Hopper', 'Cobol Systems', 'Active', Link::make('Open')->href('/?page=customers&id=3')],
                ])
                ->striped()
                ->hover(),
            Pagination::make()
                ->currentPage(1)
                ->totalPages(4)
                ->urlForPage(static fn (int $p): string => '/?page=customers&p=' . $p),
            Text::make('Featured accounts')->as('h2')->size('lg')->weight('semibold'),
            Row::make([
                CustomerCard::make()
                    ->name('Ada Lovelace')
                    ->email('ada@analytical.example')
                    ->initials('AL')
                    ->status('Active', 'success')
                    ->href('/?page=customers&id=1'),
                CustomerCard::make()
                    ->name('Alan Turing')
                    ->email('alan@bombe.example')
                    ->initials('AT')
                    ->status('Lead', 'warning')
                    ->href('/?page=customers&id=2'),
                CustomerCard::make()
                    ->name('Grace Hopper')
                    ->email('grace@cobol.example')
                    ->initials('GH')
                    ->status('Active', 'success')
                    ->href('/?page=customers&id=3'),
            ])->gap(4),
        ])->gap(4);

        return Document::make()
            ->title('Customers · Serixa CRM')
            ->description('Customer directory')
            ->language('en')
            ->body(CrmLayout::shell('customers', $body));
    }
}
