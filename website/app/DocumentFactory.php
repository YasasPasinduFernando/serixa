<?php

declare(strict_types=1);

namespace App;

use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;
use Serixa\Styling\StyleSheet;

/**
 * Builds every page Document: SEO title, OG/Twitter, canonical, CSS, runtime.
 */
final class DocumentFactory
{
    private const SITE_NAME = 'SERIXA';

    private const DEFAULT_DESCRIPTION = 'SERIXA — Flutter-inspired PHP UI engine. Server-rendered widgets, semantic HTML, offline CSS compiler, progressive enhancement.';

    private function __construct()
    {
    }

    public static function siteUrl(): string
    {
        $env = getenv('SERIXA_SITE_URL');
        if (is_string($env) && $env !== '') {
            return rtrim($env, '/');
        }

        return 'https://serixa.yasaboy.com';
    }

    public static function make(string $title, WidgetInterface $body, string $description = '', string $path = '/'): Document
    {
        $fullTitle = $title === '' ? self::SITE_NAME : sprintf('%s · %s', $title, self::SITE_NAME);
        $desc = $description !== '' ? $description : self::DEFAULT_DESCRIPTION;
        $canonical = self::siteUrl() . ($path === '/' ? '/' : '/' . ltrim($path, '/'));

        $document = Document::make()
            ->title($fullTitle)
            ->description($desc)
            ->themeColor('#0f172a')
            ->language('en')
            ->canonical($canonical)
            ->robots('index,follow')
            ->favicon('/favicon.svg')
            ->openGraph('og:type', 'website')
            ->openGraph('og:site_name', self::SITE_NAME)
            ->openGraph('og:title', $fullTitle)
            ->openGraph('og:description', $desc)
            ->openGraph('og:url', $canonical)
            ->twitter('twitter:card', 'summary')
            ->twitter('twitter:title', $fullTitle)
            ->twitter('twitter:description', $desc)
            ->jsonLd(self::jsonLd($fullTitle, $desc, $canonical))
            ->body($body);

        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets());
        $document->assets()->inlineScript(<<<'JS'
document.addEventListener('click',function(e){var b=e.target.closest('[data-serixa-copy]');if(!b)return;var c=b.parentElement&&b.parentElement.querySelector('code');if(!c||!navigator.clipboard)return;navigator.clipboard.writeText(c.textContent||'').then(function(){b.textContent='Copied';setTimeout(function(){b.textContent='Copy'},1500)});});
JS);

        return $document;
    }

    private static function jsonLd(string $title, string $description, string $url): string
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => self::SITE_NAME,
            'url' => self::siteUrl() . '/',
            'description' => $description,
            'publisher' => [
                '@type' => 'Organization',
                'name' => self::SITE_NAME,
                'url' => 'https://github.com/YasasPasinduFernando/serixa',
            ],
        ];

        if ($url !== self::siteUrl() . '/') {
            $payload['mainEntity'] = [
                '@type' => 'WebPage',
                'name' => $title,
                'url' => $url,
            ];
        }

        return (string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
