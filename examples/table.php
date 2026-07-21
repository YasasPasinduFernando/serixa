<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\Pagination;
use Serixa\Component\SearchField;
use Serixa\Component\Toolbar;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$page = Column::make([
    PageHeader::make()->title('Users')->actions(Button::make('Create')->primary()),
    Toolbar::make()
        ->leading(SearchField::make()->name('q')->placeholder('Search…'))
        ->trailing(Button::make('Export')->secondary()),
    DataTable::make()
        ->headers(['Name', 'Email', 'Actions'])
        ->rows([
            ['Ada Lovelace', 'ada@example.com', Link::make('Edit')->href('#')],
            ['Alan Turing', 'alan@example.com', Link::make('Edit')->href('#')],
        ])
        ->striped()
        ->emptyState(EmptyState::make()->title('No users')),
    Pagination::make()
        ->currentPage(1)
        ->totalPages(3)
        ->urlForPage(static fn (int $p): string => '/?p=' . $p),
])->gap(4);

$document = Document::make()->title('Table · SERIXA')->body($page);
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
