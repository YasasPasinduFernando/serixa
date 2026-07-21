<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\CrmLayout;
use Serixa\Component\Accordion;
use Serixa\Component\AccordionItem;
use Serixa\Component\Checkbox;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Document\Document;

/**
 * Task board grouped with accordion sections and checkboxes.
 */
final class TasksPage
{
    public static function document(): Document
    {
        $body = Column::make([
            PageHeader::make()
                ->title('Tasks')
                ->subtitle('Follow-ups and to-dos'),
            Accordion::make()
                ->item(
                    AccordionItem::make()
                        ->title('Today')
                        ->content(
                            Column::make([
                                Checkbox::make()->name('task_1')->label('Call Acme about renewal')->checked(),
                                Checkbox::make()->name('task_2')->label('Send proposal to Northwind'),
                                Checkbox::make()->name('task_3')->label('Update Contoso contact notes'),
                            ])->gap(2),
                        )
                        ->open(true),
                )
                ->item(
                    AccordionItem::make()
                        ->title('This week')
                        ->content(
                            Column::make([
                                Checkbox::make()->name('task_4')->label('Prepare Q3 forecast'),
                                Checkbox::make()->name('task_5')->label('Schedule demo with Fabrikam'),
                            ])->gap(2),
                        ),
                )
                ->item(
                    AccordionItem::make()
                        ->title('Backlog')
                        ->content(
                            Column::make([
                                Checkbox::make()->name('task_6')->label('Clean duplicate leads'),
                                Checkbox::make()->name('task_7')->label('Review overdue invoices'),
                            ])->gap(2),
                        ),
                ),
        ])->gap(4);

        return Document::make()
            ->title('Tasks · Serixa CRM')
            ->description('Tasks')
            ->language('en')
            ->body(CrmLayout::shell('tasks', $body));
    }
}
