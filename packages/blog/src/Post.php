<?php

declare(strict_types=1);

namespace Serixa\Blog;

use DateTimeImmutable;
use Serixa\Cms\ContentBlock;
use Serixa\Cms\ContentStatus;
use Serixa\Cms\Page;
use Serixa\Exception\InvalidArgumentException;

final class Post
{
    /** @var list<string> */
    private array $categoryIds = [];

    /** @var list<ContentBlock> */
    private array $blocks = [];

    private ContentStatus $status = ContentStatus::Draft;

    private ?DateTimeImmutable $publishedAt = null;

    public function __construct(
        private readonly string $id,
        private string $slug,
        private string $title,
        private string $excerpt = '',
        private ?string $authorId = null,
    ) {
        if ($id === '' || $slug === '' || $title === '') {
            throw new InvalidArgumentException('Post id, slug, and title are required.');
        }
    }

    public static function make(
        string $id,
        string $slug,
        string $title,
        string $excerpt = '',
        ?string $authorId = null,
    ): self {
        return new self($id, $slug, $title, $excerpt, $authorId);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function excerpt(): string
    {
        return $this->excerpt;
    }

    public function authorId(): ?string
    {
        return $this->authorId;
    }

    public function status(): ContentStatus
    {
        return $this->status;
    }

    public function publishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @return list<string>
     */
    public function categoryIds(): array
    {
        return $this->categoryIds;
    }

    /**
     * @return list<ContentBlock>
     */
    public function blocks(): array
    {
        return $this->blocks;
    }

    public function addCategory(string $categoryId): self
    {
        if (! in_array($categoryId, $this->categoryIds, true)) {
            $this->categoryIds[] = $categoryId;
        }

        return $this;
    }

    public function addBlock(ContentBlock $block): self
    {
        $this->blocks[] = $block;

        return $this;
    }

    public function publish(?DateTimeImmutable $at = null): self
    {
        $this->status = ContentStatus::Published;
        $this->publishedAt = $at ?? new DateTimeImmutable();

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->status === ContentStatus::Published;
    }

    /**
     * Project this post onto a CMS page (slug prefixed with blog/).
     */
    public function toCmsPage(): Page
    {
        $page = Page::make('blog-' . $this->id, 'blog/' . $this->slug, $this->title);
        foreach ($this->blocks as $block) {
            $page->addBlock($block);
        }
        if ($this->isPublished()) {
            $page->publish($this->publishedAt);
        }

        return $page;
    }
}
