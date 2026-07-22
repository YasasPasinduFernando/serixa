<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\DevTools\DevTools;
use Serixa\DevTools\Theme\DevToolsTheme;
use Serixa\Document\Document;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::reset();
ThemeManager::set(new DevToolsTheme(new DefaultTheme()));
DevTools::reset();

/**
 * @param list<\Serixa\Contracts\WidgetInterface|string> $children
 */
function devtools_example_document(string $title, array $children, bool $withDevToolsAssets = true): Document
{
    $document = Document::make()->title($title . ' · SERIXA DevTools')->body(
        Container::make(
            Column::make(...$children)->gap(6),
        )->padding('lg'),
    );

    $document->assets()->js('https://cdn.tailwindcss.com');
    $document->assets()->js('/assets/serixa.js', ['defer' => true]);

    if ($withDevToolsAssets && DevTools::enabled()) {
        return DevTools::attach($document);
    }

    return $document;
}
