<?php

declare(strict_types=1);

namespace SerixaDemo;

use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

/**
 * Shared document shell for demo pages (SEO + assets).
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

        // Tailwind Play CDN so theme utility classes from SERIXA are visible.
        // DX note: no first-class “useTailwindCdn()” helper on Document.
        $document->assets()
            ->js('https://cdn.tailwindcss.com')
            ->css('/assets/demo.css');

        ClientRuntime::register($document->assets());

        return $document;
    }
}
