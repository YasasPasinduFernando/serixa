<?php

declare(strict_types=1);

namespace Serixa\Meilisearch\Contracts;

use Serixa\Meilisearch\SearchResult;

/**
 * Shared full-text search contract for Meilisearch and remote engine adapters.
 */
interface SearchEngine
{
    /**
     * @param array<string, mixed> $document
     */
    public function index(string $index, string $id, array $document): void;

    public function delete(string $index, string $id): void;

    /**
     * @param array<string, mixed> $options
     */
    public function search(string $index, string $query, array $options = []): SearchResult;

    public function clear(string $index): void;
}
