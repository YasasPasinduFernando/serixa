<?php

declare(strict_types=1);

namespace Serixa\Meilisearch;

use Serixa\Meilisearch\Contracts\SearchEngine;
use Serixa\Meilisearch\Exceptions\SearchException;

/**
 * In-memory search engine for tests and offline demos.
 */
final class MemorySearchEngine implements SearchEngine
{
    /** @var array<string, array<string, array<string, mixed>>> */
    private array $indexes = [];

    public function index(string $index, string $id, array $document): void
    {
        $index = $this->normalizeIndex($index);
        $id = $this->normalizeId($id);
        $document['id'] = $id;
        $this->indexes[$index][$id] = $document;
    }

    public function delete(string $index, string $id): void
    {
        $index = $this->normalizeIndex($index);
        $id = $this->normalizeId($id);
        unset($this->indexes[$index][$id]);
    }

    public function search(string $index, string $query, array $options = []): SearchResult
    {
        $index = $this->normalizeIndex($index);
        $limit = max(1, (int) ($options['limit'] ?? 20));
        $needle = mb_strtolower(trim($query));
        $hits = [];

        foreach ($this->indexes[$index] ?? [] as $document) {
            if ($needle === '' || $this->matches($document, $needle)) {
                $hits[] = $document;
            }
        }

        $total = count($hits);
        $hits = array_slice($hits, 0, $limit);

        return new SearchResult($query, array_values($hits), $total);
    }

    public function clear(string $index): void
    {
        unset($this->indexes[$this->normalizeIndex($index)]);
    }

    /**
     * @param array<string, mixed> $document
     */
    private function matches(array $document, string $needle): bool
    {
        foreach ($document as $value) {
            if (is_scalar($value) && str_contains(mb_strtolower((string) $value), $needle)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeIndex(string $index): string
    {
        $index = trim($index);
        if ($index === '' || preg_match('/[^a-zA-Z0-9_\-]/', $index) === 1) {
            throw SearchException::invalidIndex($index);
        }

        return $index;
    }

    private function normalizeId(string $id): string
    {
        $id = trim($id);
        if ($id === '') {
            throw SearchException::invalidDocumentId($id);
        }

        return $id;
    }
}
