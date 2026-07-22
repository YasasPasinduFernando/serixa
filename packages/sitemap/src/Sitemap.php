<?php

declare(strict_types=1);

namespace Serixa\Sitemap;

final class Sitemap
{
    /** @var list<SitemapUrl> */
    private array $urls = [];

    public static function make(): self
    {
        return new self();
    }

    public function add(SitemapUrl $url): self
    {
        $this->urls[] = $url;

        return $this;
    }

    /**
     * @return list<SitemapUrl>
     */
    public function urls(): array
    {
        return $this->urls;
    }

    public function count(): int
    {
        return count($this->urls);
    }

    public function toXml(): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($this->urls as $url) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>' . htmlspecialchars($url->loc(), ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc>';
            if ($url->lastmod() !== null) {
                $lines[] = '    <lastmod>' . $url->lastmod()->format('Y-m-d') . '</lastmod>';
            }
            if ($url->changefreq() !== null) {
                $lines[] = '    <changefreq>' . htmlspecialchars($url->changefreq(), ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</changefreq>';
            }
            if ($url->priority() !== null) {
                $lines[] = '    <priority>' . number_format($url->priority(), 1, '.', '') . '</priority>';
            }
            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines) . "\n";
    }
}
