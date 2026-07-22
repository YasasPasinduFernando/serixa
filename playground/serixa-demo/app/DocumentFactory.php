<?php

declare(strict_types=1);

namespace SerixaDemo;

use Serixa\Styling\StyleSheet;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

/**
 * Shared document shell for demo pages (SEO + compiled SERIXA CSS).
 */
final class DocumentFactory
{
    public static function page(string $title, WidgetInterface $body): Document
    {
        $document = Document::make()
            ->title($title . ' · SERIXA Demo')
            ->description('SERIXA framework DX playground')
            ->language('en')
            ->themeColor('#0f766e')
            ->body($body);

        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets());

        return $document;
    }
}
