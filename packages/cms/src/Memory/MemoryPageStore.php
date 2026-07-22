<?php

declare(strict_types=1);

namespace Serixa\Cms\Memory;

use Serixa\Cms\ContentStatus;
use Serixa\Cms\Contracts\PageStore;
use Serixa\Cms\Page;

final class MemoryPageStore implements PageStore
{
    /** @var array<string, Page> */
    private array $pages = [];

    public function put(Page $page): void
    {
        $this->pages[$page->id()] = $page;
    }

    public function get(string $id): ?Page
    {
        return $this->pages[$id] ?? null;
    }

    public function findBySlug(string $slug): ?Page
    {
        foreach ($this->pages as $page) {
            if ($page->slug() === $slug) {
                return $page;
            }
        }

        return null;
    }

    public function all(): array
    {
        return array_values($this->pages);
    }

    public function published(): array
    {
        $out = [];
        foreach ($this->pages as $page) {
            if ($page->status() === ContentStatus::Published) {
                $out[] = $page;
            }
        }

        return $out;
    }

    public function remove(string $id): void
    {
        unset($this->pages[$id]);
    }
}
