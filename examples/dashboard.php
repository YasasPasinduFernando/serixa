<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\ActivityItem;
use Serixa\Component\ActivityList;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\StatCard;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$page = Column::make([
    PageHeader::make()->title('Dashboard')->subtitle('Last 7 days'),
    Row::make([
        StatCard::make()->title('Users')->value('1,284')->trend('+3%')->color('primary'),
        StatCard::make()->title('Revenue')->value('$48k')->color('success'),
    ])->gap(4),
    ActivityList::make(
        ActivityItem::make()->title('Deploy finished')->timestamp('1h ago')->icon('•'),
        ActivityItem::make()->title('User invited')->timestamp('3h ago')->icon('•'),
    ),
])->gap(4);

$document = Document::make()->title('Dashboard · SERIXA')->body($page);
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
