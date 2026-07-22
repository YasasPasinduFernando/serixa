<?php

declare(strict_types=1);

namespace App;

use Serixa\Assets\StyleSheet;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

/**
 * Builds a fully-configured {@see Document}: SEO title, compiled SERIXA CSS, runtime.
 */
final class DocumentFactory
{
    private const SITE_NAME = 'SERIXA Admin';

    public static function make(string $title, WidgetInterface $body): Document
    {
        $document = Document::make()
            ->title($title === '' ? self::SITE_NAME : sprintf('%s · %s', $title, self::SITE_NAME))
            ->description('Official SERIXA Admin Starter — a production-ready administration panel scaffold built entirely with serixa/framework, serixa/admin and serixa/icons.')
            ->themeColor('#0f766e')
            ->body($body);

        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets());

        return $document;
    }
}
