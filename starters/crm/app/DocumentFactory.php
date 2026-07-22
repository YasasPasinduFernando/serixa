<?php

declare(strict_types=1);

namespace App;

use Serixa\Calendar\Runtime\CalendarRuntime;
use Serixa\Charts\Runtime\ChartRuntime;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;
use Serixa\Styling\StyleSheet;

/**
 * Builds a fully-configured {@see Document}: SEO title, offline CSS, runtimes.
 */
final class DocumentFactory
{
    private const SITE_NAME = 'SERIXA CRM';

    public static function make(string $title, WidgetInterface $body): Document
    {
        $document = Document::make()
            ->title($title === '' ? self::SITE_NAME : sprintf('%s · %s', $title, self::SITE_NAME))
            ->description('Official SERIXA CRM Starter — sales pipeline UI demo with fixtures only (no database).')
            ->themeColor('#0f766e')
            ->body($body);

        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets());
        ChartRuntime::register($document->assets(), '/assets/charts/charts.js');
        CalendarRuntime::register($document->assets(), '/assets/calendar/calendar.js');

        return $document;
    }
}
