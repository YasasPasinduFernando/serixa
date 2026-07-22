<?php

declare(strict_types=1);

namespace App;

use Serixa\Component\Column;
use Serixa\Component\Container;
use Serixa\Component\Divider;
use Serixa\Component\Row;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;

/**
 * Documentation chrome: top nav + left sidebar + article column.
 */
final class DocsLayout
{
    private function __construct()
    {
    }

    public static function wrap(string $slug, WidgetInterface $article, string $title): Document
    {
        $theme = SiteContext::theme();
        $entry = DocsCatalog::find($slug);
        $group = $entry['group'] ?? 'Docs';

        $body = Column::make([
            Breadcrumbs::make([
                ['label' => 'Home', 'href' => '?page=home'],
                ['label' => 'Docs', 'href' => DocsCatalog::href('introduction')],
                ['label' => $group, 'href' => null],
                ['label' => $title, 'href' => null],
            ]),
            $article,
            Divider::make(),
            DocPager::make($slug),
        ])->gap(6)->alignStart()->class('docs-article');

        // Full-bleed shell; constrain only the docs frame content width.
        $shell = Column::make([
            SiteNav::make(DocsCatalog::route($slug), $theme)->class('site-header'),
            Container::make(
                Row::make([
                    DocsSidebar::make($slug),
                    Container::make($body)->maxWidth('full')->padding('none')->class('docs-content'),
                ])->gap(8)->alignStart()->class('docs-grid'),
            )->maxWidth('xl')->class('docs-frame'),
        ])
            ->gap(0)
            ->alignStretch()
            ->class($theme === 'dark' ? 'site-shell dark' : 'site-shell')
            ->attribute('data-theme', $theme);

        return DocumentFactory::make($title, $shell, 'SERIXA documentation — ' . $title);
    }
}
