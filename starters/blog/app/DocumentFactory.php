<?php

declare(strict_types=1);

namespace App;

use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

/**
 * Builds a fully-configured {@see Document} for every page: SEO title, the
 * Tailwind CDN build, and the SERIXA progressive-enhancement runtime.
 */
final class DocumentFactory
{
    private const SITE_NAME = 'SERIXA Blog';

    public static function make(string $title, WidgetInterface $body): Document
    {
        $document = Document::make()
            ->title($title === '' ? self::SITE_NAME : sprintf('%s · %s', $title, self::SITE_NAME))
            ->description('Official SERIXA Blog Starter — a production-ready blog CMS scaffold built entirely with serixa/framework, serixa/admin and serixa/icons.')
            ->themeColor('#4f46e5')
            ->body($body);

        $document->assets()->js('https://cdn.tailwindcss.com');
        $document->assets()->css('/app.css');
        ClientRuntime::register($document->assets());

        return $document;
    }
}
