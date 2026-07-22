<?php

declare(strict_types=1);

namespace Serixa\Typesense;

use Serixa\Meilisearch\MemorySearchEngine;
use Serixa\Typesense\Contracts\TypesenseClient;

final class FakeTypesenseClient implements TypesenseClient
{
    private MemorySearchEngine $engine;

    public function __construct(?MemorySearchEngine $engine = null)
    {
        $this->engine = $engine ?? new MemorySearchEngine();
    }

    public function upsertDocument(string $collection, array $document): void
    {
        $id = (string) ($document['id'] ?? '');
        $this->engine->index($collection, $id, $document);
    }

    public function deleteDocument(string $collection, string $id): void
    {
        $this->engine->delete($collection, $id);
    }

    public function search(string $collection, string $query, array $options = []): array
    {
        $limit = (int) ($options['per_page'] ?? $options['limit'] ?? 20);
        $result = $this->engine->search($collection, $query, ['limit' => $limit]);
        $hits = [];
        foreach ($result->hits as $document) {
            $hits[] = ['document' => $document];
        }

        return [
            'hits' => $hits,
            'found' => $result->estimatedTotalHits,
        ];
    }

    public function deleteCollection(string $collection): void
    {
        $this->engine->clear($collection);
    }
}
