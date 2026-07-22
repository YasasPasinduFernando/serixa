<?php

declare(strict_types=1);

namespace Serixa\Meilisearch;

use Serixa\Meilisearch\Contracts\MeilisearchClient;

final class FakeMeilisearchClient implements MeilisearchClient
{
    private MemorySearchEngine $engine;

    public function __construct(?MemorySearchEngine $engine = null)
    {
        $this->engine = $engine ?? new MemorySearchEngine();
    }

    public function addDocuments(string $index, array $documents, string $primaryKey = 'id'): void
    {
        foreach ($documents as $document) {
            $id = (string) ($document[$primaryKey] ?? '');
            $this->engine->index($index, $id, $document);
        }
    }

    public function deleteDocument(string $index, string $id): void
    {
        $this->engine->delete($index, $id);
    }

    public function search(string $index, string $query, array $options = []): array
    {
        $result = $this->engine->search($index, $query, $options);

        return [
            'hits' => $result->hits,
            'estimatedTotalHits' => $result->estimatedTotalHits,
        ];
    }

    public function deleteIndex(string $index): void
    {
        $this->engine->clear($index);
    }
}
