<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Assets\StyleSheet;

use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

$renderer = new Renderer();
$colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'muted', 'inherit'];
$row = [];

foreach ($colors as $color) {
    $row[] = Column::make([
        Icon::make('star')->size('lg')->color($color),
        Text::make($color)->size('sm'),
    ])->gap(1)->alignCenter();
}

$html = $renderer->render(
    Column::make([
        Text::make('Icon theming')->as('h1')->size('xl')->weight('bold'),
        Text::make('Semantic colors via DefaultTheme icon map')->tone('muted'),
        Row::make($row)->gap(6),
        Row::make([
            Icon::make('check-circle')->size('xl')->color('success')->filled(),
            Icon::make('x-circle')->size('xl')->color('danger')->outlined()->strokeWidth(2),
        ])->gap(4),
    ])->gap(4),
);

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><link rel="stylesheet" href="/assets/serixa.css">';
echo '<title>Icon theming</title></head><body class="p-8 text-slate-900">';
echo $html;
echo '</body></html>';
