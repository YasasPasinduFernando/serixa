<?php

declare(strict_types=1);

namespace Serixa\Blog\Memory;

use Serixa\Blog\Category;
use Serixa\Blog\Contracts\BlogStore;
use Serixa\Blog\Post;
use Serixa\Cms\ContentStatus;

final class MemoryBlogStore implements BlogStore
{
    /** @var array<string, Category> */
    private array $categories = [];

    /** @var array<string, Post> */
    private array $posts = [];

    public function putCategory(Category $category): void
    {
        $this->categories[$category->id()] = $category;
    }

    public function getCategory(string $id): ?Category
    {
        return $this->categories[$id] ?? null;
    }

    public function categories(): array
    {
        return array_values($this->categories);
    }

    public function put(Post $post): void
    {
        $this->posts[$post->id()] = $post;
    }

    public function get(string $id): ?Post
    {
        return $this->posts[$id] ?? null;
    }

    public function findBySlug(string $slug): ?Post
    {
        foreach ($this->posts as $post) {
            if ($post->slug() === $slug) {
                return $post;
            }
        }

        return null;
    }

    public function all(): array
    {
        return array_values($this->posts);
    }

    public function published(): array
    {
        $out = [];
        foreach ($this->posts as $post) {
            if ($post->status() === ContentStatus::Published) {
                $out[] = $post;
            }
        }

        return $out;
    }
}
