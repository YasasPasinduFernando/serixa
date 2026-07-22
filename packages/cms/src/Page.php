<?php

declare(strict_types=1);

namespace Serixa\Cms;

use DateTimeImmutable;
use Serixa\Cms\Exceptions\CmsException;
use Serixa\Exception\InvalidArgumentException;

final class Page
{
    /** @var list<ContentBlock> */
    private array $blocks = [];

    private ContentStatus $status = ContentStatus::Draft;

    private ?DateTimeImmutable $publishedAt = null;

    public function __construct(
        private readonly string $id,
        private string $slug,
        private string $title,
    ) {
        if ($id === '' || $slug === '' || $title === '') {
            throw new InvalidArgumentException('Page id, slug, and title are required.');
        }
    }

    public static function make(string $id, string $slug, string $title): self
    {
        return new self($id, $slug, $title);
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

    public function status(): ContentStatus
    {
        return $this->status;
    }

    public function publishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function rename(string $title): self
    {
        if ($title === '') {
            throw new InvalidArgumentException('Page title cannot be empty.');
        }
        $this->title = $title;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        if ($slug === '') {
            throw new InvalidArgumentException('Page slug cannot be empty.');
        }
        $this->slug = $slug;

        return $this;
    }

    public function addBlock(ContentBlock $block): self
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * @return list<ContentBlock>
     */
    public function blocks(): array
    {
        return $this->blocks;
    }

    public function publish(?DateTimeImmutable $at = null): self
    {
        $this->status = ContentStatus::Published;
        $this->publishedAt = $at ?? new DateTimeImmutable();

        return $this;
    }

    public function unpublish(): self
    {
        if ($this->status === ContentStatus::Archived) {
            throw CmsException::invalidTransition($this->status->value, ContentStatus::Draft->value);
        }
        $this->status = ContentStatus::Draft;
        $this->publishedAt = null;

        return $this;
    }

    public function archive(): self
    {
        $this->status = ContentStatus::Archived;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->status === ContentStatus::Published;
    }
}
