<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\Modal;
use Serixa\Component\PageHeader;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Invoices table with status badges and a pay stub modal.
 */
final class InvoicesPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Invoices')
                ->subtitle('Billing and collections')
                ->actions(
                    Button::make('Record payment')
                        ->primary()
                        ->attribute('data-serixa-open-modal', 'pay-modal'),
                ),
            DataTable::make()
                ->headers(['Invoice', 'Customer', 'Amount', 'Status', 'Due'])
                ->rows([
                    [
                        'INV-1042',
                        'Acme Corp',
                        '$4,200',
                        Badge::make('Paid')->color('success'),
                        '2026-07-01',
                    ],
                    [
                        'INV-1043',
                        'Northwind',
                        '$1,850',
                        Badge::make('Due')->color('warning'),
                        '2026-07-25',
                    ],
                    [
                        'INV-1044',
                        'Contoso',
                        '$980',
                        Badge::make('Overdue')->color('danger'),
                        '2026-07-10',
                    ],
                    [
                        'INV-1045',
                        'Fabrikam',
                        '$3,100',
                        Badge::make('Draft')->color('neutral'),
                        '—',
                    ],
                ])
                ->striped()
                ->hover(),
            Modal::make()
                ->id('pay-modal')
                ->title('Record payment')
                ->body(
                    Column::make([
                        Text::make('Payment recording is a stub for validation — no backend.'),
                        Text::make('Invoice INV-1043 · $1,850')->tone('muted'),
                    ])->gap(2),
                )
                ->footer(
                    Button::make('Cancel')->ghost()->attribute('data-serixa-close', true),
                    Button::make('Confirm payment')->primary()->attribute('data-serixa-close', true),
                )
                ->open(false),
        ])->gap(4);

        return Document::make()
            ->title('Invoices · Serixa CRM')
            ->description('Invoices')
            ->language('en')
            ->body(CrmLayout::shell('invoices', $body));
    }
}
