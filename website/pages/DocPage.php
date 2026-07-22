<?php

declare(strict_types=1);

namespace App;

use App\Support\Markdown;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;

/**
 * Renders a documentation page from the monorepo /docs markdown sources.
 */
final class DocPage
{
    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $query
     */
    public static function document(string $slug, array $query = []): Document
    {
        unset($query);
        $entry = DocsCatalog::find($slug);

        if ($entry === null) {
            return NotFoundPage::document();
        }

        $html = Markdown::fileToHtml(DocsCatalog::resolvePath($entry['source']));

        $article = Column::make([
            Text::make($entry['title'])->as('h1')->size('2xl')->weight('bold'),
            Text::make($entry['group'])->as('p')->size('sm')->tone('muted'),
            ProseHtml::make($html),
        ])->gap(4)->alignStart();

        return DocsLayout::wrap($slug, $article, $entry['title']);
    }
}
