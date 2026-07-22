<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Charts\Runtime\ChartRuntime;
use Serixa\Charts\Theme\ChartsTheme;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Document\Document;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::reset();
ThemeManager::set(new ChartsTheme(new DefaultTheme()));

/**
 * @param list<\Serixa\Contracts\WidgetInterface|string> $children
 */
function charts_example_document(string $title, array $children): Document
{
    $document = Document::make()->title($title . ' · SERIXA Charts')->body(
        Container::make(
            Column::make(...$children)->gap(6),
        )->padding('lg'),
    );

    $document->assets()->js('https://cdn.tailwindcss.com');
    $document->assets()->js('/assets/serixa.js', ['defer' => true]);
    ChartRuntime::register($document->assets(), '/assets/charts/charts.js');

    return $document;
}
