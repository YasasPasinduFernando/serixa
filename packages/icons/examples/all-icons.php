<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Icons\Support\OutlineCatalog;
use Serixa\Rendering\Renderer;

$renderer = new Renderer();
$cells = [];

foreach (OutlineCatalog::names() as $name) {
    $cells[] = Column::make([
        Icon::make($name)->size('md')->color('muted'),
        Text::make($name)->size('xs')->tone('muted'),
    ])->gap(1)->alignCenter();
}

$html = $renderer->render(
    Column::make([
        Text::make('SERIXA Icons — full catalog (' . count(OutlineCatalog::names()) . ')')->as('h1')->size('xl')->weight('bold'),
        Row::make($cells)->gap(4),
    ])->gap(6),
);

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><script src="https://cdn.tailwindcss.com"></script>';
echo '<title>All SERIXA Icons</title></head><body class="p-8">';
echo $html;
echo '</body></html>';
