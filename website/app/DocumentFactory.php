<?php

declare(strict_types=1);

namespace App;

use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

/**
 * Builds every page Document: SEO title, Tailwind utilities, site CSS, client runtime.
 *
 * Theme classes from DefaultTheme are Tailwind utilities. The Play CDN build is
 * served locally (public/assets/vendor/tailwindcss.js) without defer so styles
 * apply even when cdn.tailwindcss.com is blocked or fails to load.
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

        // Local Tailwind browser build — must not defer (Play CDN injects styles on execute).
        $document->assets()->js('/assets/vendor/tailwindcss.js', ['defer' => false]);
        $document->assets()->css('/app.css');
        ClientRuntime::register($document->assets());

        return $document;
    }
}
