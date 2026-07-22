<?php

declare(strict_types=1);

namespace Serixa\Cms;

use Serixa\Cms\Contracts\PageStore;
use Serixa\Cms\Exceptions\CmsException;
use Serixa\Cms\Memory\MemoryPageStore;

final class Cms
{
    public function __construct(private readonly PageStore $store)
    {
    }

    public static function memory(): self
    {
        return new self(new MemoryPageStore());
    }

    public function store(): PageStore
    {
        return $this->store;
    }

    public function create(string $id, string $slug, string $title): Page
    {
        $page = Page::make($id, $slug, $title);
        $this->store->put($page);

        return $page;
    }

    public function require(string $id): Page
    {
        $page = $this->store->get($id);
        if ($page === null) {
            throw CmsException::notFound($id);
        }

        return $page;
    }
}
