<?php

declare(strict_types=1);

namespace Serixa\Blog\Contracts;

use Serixa\Blog\Category;
use Serixa\Blog\Post;

interface BlogStore
{
    public function putCategory(Category $category): void;

    public function getCategory(string $id): ?Category;

    /**
     * @return list<Category>
     */
    public function categories(): array;

    public function put(Post $post): void;

    public function get(string $id): ?Post;

    public function findBySlug(string $slug): ?Post;

    /**
     * @return list<Post>
     */
    public function all(): array;

    /**
     * @return list<Post>
     */
    public function published(): array;
}
