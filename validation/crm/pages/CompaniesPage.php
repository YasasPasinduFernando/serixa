<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\SearchField;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;

/**
 * Companies directory table.
 */
final class CompaniesPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Companies')
                ->subtitle('Accounts and organizations')
                ->actions(Button::make('Add company')->primary()),
            Toolbar::make()
                ->leading(SearchField::make()->name('q')->placeholder('Search companies…')),
            DataTable::make()
                ->headers(['Company', 'Industry', 'Employees', 'Owner', 'Actions'])
                ->rows([
                    ['Acme Corp', 'Manufacturing', '120', 'Ada Lovelace', Link::make('View')->href('#')],
                    ['Northwind Traders', 'Wholesale', '45', 'Alan Turing', Link::make('View')->href('#')],
                    ['Contoso Ltd', 'Software', '890', 'Grace Hopper', Link::make('View')->href('#')],
                    ['Fabrikam', 'Services', '210', 'Jordan Lee', Link::make('View')->href('#')],
                ])
                ->striped()
                ->hover(),
        ])->gap(4);

        return Document::make()
            ->title('Companies · Serixa CRM')
            ->description('Companies directory')
            ->language('en')
            ->body(CrmLayout::shell('companies', $body));
    }
}
