<?php

declare(strict_types=1);

namespace App;

use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

/**
 * Builds every page Document: SEO title, Tailwind CDN, site CSS, client runtime.
 */
final class DocumentFactory
{
    private const SITE_NAME = 'SERIXA';

    private function __construct()
    {
    }

    public static function make(string $title, WidgetInterface $body, string $description = ''): Document
    {
        $document = Document::make()
            ->title($title === '' ? self::SITE_NAME : sprintf('%s · %s', $title, self::SITE_NAME))
            ->description($description !== '' ? $description : 'Official SERIXA documentation — modern SSR UI for PHP.')
            ->themeColor('#0f172a')
            ->language('en')
            ->body($body);

        $document->assets()->js('https://cdn.tailwindcss.com');
        $document->assets()->css('/app.css');
        ClientRuntime::register($document->assets());

        return $document;
    }
}
