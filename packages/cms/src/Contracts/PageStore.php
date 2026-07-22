<?php

declare(strict_types=1);

namespace Serixa\Cms\Contracts;

use Serixa\Cms\Page;

interface PageStore
{
    public function put(Page $page): void;

    public function get(string $id): ?Page;

    public function findBySlug(string $slug): ?Page;

    /**
     * @return list<Page>
     */
    public function all(): array;

    /**
     * @return list<Page>
     */
    public function published(): array;

    public function remove(string $id): void;
}
