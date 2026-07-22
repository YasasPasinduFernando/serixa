<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Styling\StyleSheet;

use Serixa\Calendar\Runtime\CalendarRuntime;
use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Document\Document;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::reset();
ThemeManager::set(new CalendarTheme(new DefaultTheme()));

/**
 * @param list<\Serixa\Contracts\WidgetInterface|string> $children
 */
function calendar_example_document(string $title, array $children): Document
{
    $document = Document::make()->title($title . ' · SERIXA Calendar')->body(
        Container::make(
            Column::make(...$children)->gap(6),
        )->padding('lg'),
    );

    StyleSheet::register($document->assets());
    $document->assets()->js('/assets/serixa.js', ['defer' => true]);
    CalendarRuntime::register($document->assets(), '/assets/calendar/calendar.js');

    return $document;
}
