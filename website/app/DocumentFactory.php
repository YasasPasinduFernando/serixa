<?php

declare(strict_types=1);

namespace App;

use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;
use Serixa\Styling\StyleSheet;

/**
 * Builds every page Document: SEO title, compiled SERIXA CSS, client runtime.
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

        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets());
        $document->assets()->inlineScript(<<<'JS'
document.addEventListener('click',function(e){var b=e.target.closest('[data-serixa-copy]');if(!b)return;var c=b.parentElement&&b.parentElement.querySelector('code');if(!c||!navigator.clipboard)return;navigator.clipboard.writeText(c.textContent||'').then(function(){b.textContent='Copied';setTimeout(function(){b.textContent='Copy'},1500)});});
JS);

        return $document;
    }
}
