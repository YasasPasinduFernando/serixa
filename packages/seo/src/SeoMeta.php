<?php

declare(strict_types=1);

namespace Serixa\Seo;

use Serixa\Document\Document;
use Serixa\Document\Seo\SeoManager;

/**
 * Content-level SEO bag that applies onto framework SeoManager.
 */
final class SeoMeta
{
    /** @var list<string> */
    private array $keywords = [];

    /** @var array<string, string> */
    private array $openGraph = [];

    /** @var array<string, string> */
    private array $twitter = [];

    public function __construct(
        private string $title = '',
        private string $description = '',
        private ?string $canonical = null,
        private ?string $author = null,
        private string $robots = 'index,follow',
    ) {
    }

    public static function make(string $title = '', string $description = ''): self
    {
        return new self($title, $description);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function canonical(string $url): self
    {
        $this->canonical = $url;

        return $this;
    }

    public function author(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function robots(string $robots): self
    {
        $this->robots = $robots;

        return $this;
    }

    /**
     * @param list<string> $keywords
     */
    public function keywords(array $keywords): self
    {
        $this->keywords = array_values($keywords);

        return $this;
    }

    public function openGraph(string $property, string $content): self
    {
        $this->openGraph[$property] = $content;

        return $this;
    }

    public function twitter(string $name, string $content): self
    {
        $this->twitter[$name] = $content;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function apply(SeoManager $seo): SeoManager
    {
        if ($this->title !== '') {
            $seo->title($this->title);
        }
        if ($this->description !== '') {
            $seo->description($this->description);
        }
        if ($this->keywords !== []) {
            $seo->keywords($this->keywords);
        }
        if ($this->canonical !== null) {
            $seo->canonical($this->canonical);
        }
        if ($this->author !== null) {
            $seo->author($this->author);
        }
        $seo->robots($this->robots);
        foreach ($this->openGraph as $property => $content) {
            $seo->openGraph($property, $content);
        }
        foreach ($this->twitter as $name => $content) {
            $seo->twitter($name, $content);
        }

        return $seo;
    }

    public function applyToDocument(Document $document): Document
    {
        $this->apply($document->seo());

        return $document;
    }
}
